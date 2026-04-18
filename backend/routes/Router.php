<?php

namespace App\Routes;

/**
 * Classe Router
 * Reçoit les requêtes HTTP et redirige vers le contrôleur et la méthode appropriés
 * en fonction de la route définie.
 */
class Router {
    /** @var array $routes Stocke la liste des routes enregistrées par l'application */
    private array $routes = [];

    /**
     * Enregistre une nouvelle route dans le système.
     * 
     * @param string $method La méthode HTTP (GET, POST, etc.)
     * @param string $path Le chemin/URL (ex: '/api/login')
     * @param string|callable $handler Le contrôleur et méthode (ex: 'AuthController@login') ou une fonction anonyme
     * @return void
     */
    public function addRoute(string $method, string $path, string|callable $handler): void {
        $this->routes[] = [
            'method'  => strtoupper($method),
            'path'    => $path,
            'handler' => $handler,
        ];
    }

    /**
     * Analyse l'URI courant et déclenche la logique/contrôleur correspondante.
     * 
     * @param string $method La méthode HTTP de l'utilisateur
     * @param string $uri L'URI requêtée par l'utilisateur
     * @return void
     */
    public function dispatch(string $method, string $uri): void {
        /**
         * NORMALISATION DE L'URI :
         * L'URI arrivant peut avoir des slashes en trop ou être vide.
         * On s'assure qu'elle commence par un '/' et qu'elle n'a pas de slash final inutile.
         */
        $uri = '/' . trim($uri, '/');
        if ($uri !== '/') {
            $uri = rtrim($uri, '/');
        }

        foreach ($this->routes as $route) {
            /**
             * MATCHING DE ROUTE AVEC PARAMÈTRES DYNAMIQUES :
             * On transforme les {param} définis dans web.php en expressions régulières (Regex).
             * Exemple : '/room/{id}' devient '#^/room/(?P<id>[^/]+)$#'
             */
            $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $route['path']);
            $pattern = '#^' . str_replace('/', '\/', $pattern) . '$#';

            if ($route['method'] === $method && preg_match($pattern, $uri, $matches)) {
                // Ne garder que les captures nommées
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                $handler = $route['handler'];

                if (is_callable($handler)) {
                    $handler($params);
                    return;
                }

                if (is_string($handler) && str_contains($handler, '@')) {
                    [$controller, $action] = explode('@', $handler);
                    $controllerClass = "App\\Controllers\\$controller";

                    if (class_exists($controllerClass)) {
                        $instance = new $controllerClass();
                        if (method_exists($instance, $action)) {
                            $instance->$action($params);
                            return;
                        }
                    }

                    // Contrôleur ou méthode introuvable
                    $this->sendError(500, "Erreur interne : contrôleur introuvable.");
                    return;
                }
            }
        }

        // Aucune route correspondante
        $isApi = str_starts_with($uri, '/api');
        if ($isApi) {
            $this->sendError(404, "Endpoint API introuvable : $uri");
        } else {
            http_response_code(404);
            require_once __DIR__ . '/../../frontend/views/404.php';
        }
    }

    /** Envoie une réponse d'erreur JSON propre */
    private function sendError(int $code, string $message): void {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => $message]);
    }
}
