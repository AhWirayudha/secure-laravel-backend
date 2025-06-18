<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class GenerateApiDocs extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'api:docs {--output=docs/API.md : Output file path}';

    /**
     * The console command description.
     */
    protected $description = 'Generate API documentation from routes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $outputPath = $this->option('output');
        
        $this->info('Generating API documentation...');
        
        $routes = collect(Route::getRoutes())->filter(function ($route) {
            return Str::startsWith($route->uri(), 'api/');
        });

        $markdown = $this->generateMarkdown($routes);
        
        // Ensure directory exists
        $directory = dirname($outputPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        file_put_contents($outputPath, $markdown);
        
        $this->info("API documentation generated: {$outputPath}");
        
        return Command::SUCCESS;
    }

    private function generateMarkdown($routes): string
    {
        $markdown = "# API Documentation\n\n";
        $markdown .= "Generated on: " . now()->format('Y-m-d H:i:s') . "\n\n";
        $markdown .= "## Base URL\n\n";
        $markdown .= "```\n" . config('app.url') . "/api\n```\n\n";
        
        $markdown .= "## Authentication\n\n";
        $markdown .= "This API uses Bearer Token authentication. Include the token in the Authorization header:\n\n";
        $markdown .= "```\nAuthorization: Bearer <your-token>\n```\n\n";
        
        $markdown .= "## Endpoints\n\n";
        
        $groupedRoutes = $routes->groupBy(function ($route) {
            $uri = $route->uri();
            if (Str::contains($uri, 'auth')) {
                return 'Authentication';
            } elseif (Str::contains($uri, 'users')) {
                return 'User Management';
            } elseif (Str::contains($uri, 'master-data')) {
                return 'Master Data';
            } elseif (Str::contains($uri, 'health')) {
                return 'Health Check';
            }
            return 'Other';
        });

        foreach ($groupedRoutes as $group => $groupRoutes) {
            $markdown .= "### {$group}\n\n";
            
            foreach ($groupRoutes as $route) {
                $methods = implode(', ', $route->methods());
                $uri = $route->uri();
                $name = $route->getName() ?? 'N/A';
                
                // Skip HEAD and OPTIONS methods
                if (in_array('HEAD', $route->methods()) && count($route->methods()) === 1) {
                    continue;
                }
                if (in_array('OPTIONS', $route->methods()) && count($route->methods()) === 1) {
                    continue;
                }
                
                $markdown .= "#### {$methods} /{$uri}\n\n";
                $markdown .= "**Route Name:** `{$name}`\n\n";
                
                // Add middleware info
                $middleware = $route->middleware();
                if (!empty($middleware)) {
                    $markdown .= "**Middleware:** " . implode(', ', $middleware) . "\n\n";
                }
                
                // Add example request/response based on route
                $markdown .= $this->getExampleForRoute($route);
                
                $markdown .= "---\n\n";
            }
        }
        
        $markdown .= "## Error Responses\n\n";
        $markdown .= "The API returns standard HTTP status codes:\n\n";
        $markdown .= "- `200` - Success\n";
        $markdown .= "- `201` - Created\n";
        $markdown .= "- `400` - Bad Request\n";
        $markdown .= "- `401` - Unauthorized\n";
        $markdown .= "- `403` - Forbidden\n";
        $markdown .= "- `404` - Not Found\n";
        $markdown .= "- `422` - Validation Error\n";
        $markdown .= "- `429` - Too Many Requests\n";
        $markdown .= "- `500` - Internal Server Error\n\n";
        
        $markdown .= "### Error Response Format\n\n";
        $markdown .= "```json\n";
        $markdown .= "{\n";
        $markdown .= "  \"message\": \"Error description\",\n";
        $markdown .= "  \"errors\": {\n";
        $markdown .= "    \"field\": [\"Validation error message\"]\n";
        $markdown .= "  }\n";
        $markdown .= "}\n";
        $markdown .= "```\n\n";
        
        return $markdown;
    }

    private function getExampleForRoute($route): string
    {
        $uri = $route->uri();
        $methods = $route->methods();
        $example = "";
        
        // Health check examples
        if (Str::contains($uri, 'health')) {
            $example .= "**Example Response:**\n\n";
            $example .= "```json\n";
            $example .= "{\n";
            $example .= "  \"status\": \"ok\",\n";
            $example .= "  \"timestamp\": \"2024-01-01T00:00:00.000000Z\",\n";
            $example .= "  \"version\": \"1.0.0\",\n";
            $example .= "  \"environment\": \"production\"\n";
            $example .= "}\n";
            $example .= "```\n\n";
        }
        
        // Auth examples
        if (Str::contains($uri, 'auth/login') && in_array('POST', $methods)) {
            $example .= "**Example Request:**\n\n";
            $example .= "```json\n";
            $example .= "{\n";
            $example .= "  \"email\": \"user@example.com\",\n";
            $example .= "  \"password\": \"password123\"\n";
            $example .= "}\n";
            $example .= "```\n\n";
            
            $example .= "**Example Response:**\n\n";
            $example .= "```json\n";
            $example .= "{\n";
            $example .= "  \"data\": {\n";
            $example .= "    \"user\": {\n";
            $example .= "      \"id\": 1,\n";
            $example .= "      \"name\": \"John Doe\",\n";
            $example .= "      \"email\": \"user@example.com\"\n";
            $example .= "    },\n";
            $example .= "    \"access_token\": \"eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...\",\n";
            $example .= "    \"token_type\": \"Bearer\"\n";
            $example .= "  },\n";
            $example .= "  \"message\": \"Login successful\"\n";
            $example .= "}\n";
            $example .= "```\n\n";
        }
        
        // Users list example
        if (Str::contains($uri, 'users') && !Str::contains($uri, '{') && in_array('GET', $methods)) {
            $example .= "**Example Response:**\n\n";
            $example .= "```json\n";
            $example .= "{\n";
            $example .= "  \"data\": [\n";
            $example .= "    {\n";
            $example .= "      \"id\": 1,\n";
            $example .= "      \"name\": \"John Doe\",\n";
            $example .= "      \"email\": \"john@example.com\",\n";
            $example .= "      \"created_at\": \"2024-01-01T00:00:00.000000Z\"\n";
            $example .= "    }\n";
            $example .= "  ],\n";
            $example .= "  \"links\": {...},\n";
            $example .= "  \"meta\": {...}\n";
            $example .= "}\n";
            $example .= "```\n\n";
        }
        
        return $example;
    }
}
