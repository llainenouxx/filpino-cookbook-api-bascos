<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->setBasePath('/filipino-cookbook-api/public');
$app->addBodyParsingMiddleware();
// Database connection helper
function getDB() {
    $host = 'localhost';
    $db   = 'filipino_cookbook_api';
    $user = 'root';
    $pass = '';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        return new PDO($dsn, $user, $pass, $options);
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
}

// Middleware for Token-Based Security
$authMiddleware = function (Request $request, $handler) {
    $authHeader = $request->getHeaderLine('Authorization');
    $expectedToken = 'Bearer dmmmsu-cookbook-token-2026';

    if (!$authHeader || $authHeader !== $expectedToken) {
        $response = new \Slim\Psr7\Response();
        $payload = json_encode([
            "status" => "error",
            "message" => "Unauthorized access. Valid API token is required."
        ]);
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(401);
    }

    return $handler->handle($request);
};

// 1. Public Welcome Route
$app->get('/', function (Request $request, Response $response) {
    $payload = json_encode([
        "message" => "Welcome to the Secured Filipino Cookbook API",
        "note" => "Use a valid Bearer token to access /api endpoints."
    ]);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

// Grouped API routes requiring security
$app->group('/api', function ($group) {
    
    // 2. Get All Foods
    $group->get('/foods', function (Request $request, Response $response) {
        $db = getDB();
        
        // Fetch foods with category and origin names
        $stmt = $db->query("
            SELECT f.food_id, f.food_name, c.category_name, o.origin_name, f.instructions 
            FROM foods f
            JOIN categories c ON f.category_id = c.category_id
            JOIN origins o ON f.origin_id = o.origin_id
        ");
        $foods = $stmt->fetchAll();

        // For each food, fetch its ingredients
        foreach ($foods as &$food) {
            $ingStmt = $db->prepare("
                SELECT i.ingredient_name 
                FROM ingredients i
                JOIN food_ingredients fi ON i.ingredient_id = fi.ingredient_id
                WHERE fi.food_id = ?
            ");
            $ingStmt->execute([$food['food_id']]);
            $food['ingredients'] = $ingStmt->fetchAll(PDO::FETCH_COLUMN);
        }

        $response->getBody()->write(json_encode($foods));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // 3. Get Food by ID
    $group->get('/foods/{id}', function (Request $request, Response $response, array $args) {
        $id = $args['id'];
        $db = getDB();
        
        $stmt = $db->prepare("
            SELECT f.food_id, f.food_name, c.category_name, o.origin_name, f.instructions 
            FROM foods f
            JOIN categories c ON f.category_id = c.category_id
            JOIN origins o ON f.origin_id = o.origin_id
            WHERE f.food_id = ?
        ");
        $stmt->execute([$id]);
        $food = $stmt->fetch();

        if (!$food) {
            $payload = json_encode([
                "status" => "error",
                "message" => "Food not found"
            ]);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        // Fetch ingredients
        $ingStmt = $db->prepare("
            SELECT i.ingredient_name 
            FROM ingredients i
            JOIN food_ingredients fi ON i.ingredient_id = fi.ingredient_id
            WHERE fi.food_id = ?
        ");
        $ingStmt->execute([$id]);
        $food['ingredients'] = $ingStmt->fetchAll(PDO::FETCH_COLUMN);

        $response->getBody()->write(json_encode($food));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // 4. Search Food by Name
    $group->get('/foods/search/{name}', function (Request $request, Response $response, array $args) {
        $name = $args['name'];
        $db = getDB();
        
        $stmt = $db->prepare("
            SELECT f.food_id, f.food_name, c.category_name, o.origin_name, f.instructions 
            FROM foods f
            JOIN categories c ON f.category_id = c.category_id
            JOIN origins o ON f.origin_id = o.origin_id
            WHERE f.food_name LIKE ?
        ");
        $stmt->execute(['%' . $name . '%']);
        $foods = $stmt->fetchAll();

        foreach ($foods as &$food) {
            $ingStmt = $db->prepare("
                SELECT i.ingredient_name 
                FROM ingredients i
                JOIN food_ingredients fi ON i.ingredient_id = fi.ingredient_id
                WHERE fi.food_id = ?
            ");
            $ingStmt->execute([$food['food_id']]);
            $food['ingredients'] = $ingStmt->fetchAll(PDO::FETCH_COLUMN);
        }

        $response->getBody()->write(json_encode($foods));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // 5. Get All Categories
    $group->get('/categories', function (Request $request, Response $response) {
        $db = getDB();
        $stmt = $db->query("SELECT * FROM categories");
        $categories = $stmt->fetchAll();
        
        $response->getBody()->write(json_encode($categories));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // 6. Get All Ingredients
    $group->get('/ingredients', function (Request $request, Response $response) {
        $db = getDB();
        $stmt = $db->query("SELECT * FROM ingredients");
        $ingredients = $stmt->fetchAll();
        
        $response->getBody()->write(json_encode($ingredients));
        return $response->withHeader('Content-Type', 'application/json');
    });
    
    // 7. Add New Food
    $group->post('/foods', function (Request $request, Response $response) {
        $data = $request->getParsedBody();
        $db = getDB();

        try {
            $db->beginTransaction();

            // Insert into foods table
            $stmt = $db->prepare(" INSERT INTO foods (food_name, category_id, origin_id, instructions) VALUES (:food_name, :category_id, :origin_id, :instructions)");

            $stmt->execute([
                'food_name' => $data['food_name'],
                'category_id' => $data['category_id'],
                'origin_id' => $data['origin_id'],
                'instructions' => $data['instructions']
            ]);
            $foodId = $db->lastInsertId();

            // Insert ingredients into food_ingredients junction table
            if (isset($data['ingredient_ids']) && is_array($data['ingredient_ids'])) {
                $ingStmt = $db->prepare("INSERT INTO food_ingredients (food_id, ingredient_id) VALUES (?, ?)");
                foreach ($data['ingredient_ids'] as $ingredientId) {
                    $ingStmt->execute([$foodId, $ingredientId]);
                }
            }

            $db->commit();

            $payload = json_encode([
                "status" => "success",
                "message" => "Food added successfully."
            ]);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);

        } catch (\Exception $e) {
            $db->rollBack();
            $payload = json_encode([
                "status" => "error",
                "message" => "Failed to add food: " . $e->getMessage()
            ]);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    });

})->add($authMiddleware);

$app->run();
