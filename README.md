# Middle Framework Skeleton

A foundational setup for [Middle Framework](https://github.com/jschreuder/Middle) that demonstrates core architectural patterns while remaining minimal and extensible.

## What This Skeleton Provides

This skeleton demonstrates Middle's core philosophy in practice:

**🔍 Explicit Architecture**: All dependencies and middleware are clearly visible in the `ServiceContainer` and routing setup. No magic, no surprises.

**🔧 Replaceable Components**: Uses proper interfaces throughout (`ControllerInterface`, `RouterInterface`) so you can swap implementations without touching other code.

**🛡️ Safe to Extend**: The middleware pipeline pattern and interface-driven design let you add features confidently without breaking existing functionality.

**🧪 Testing-First**: Comprehensive test setup with both unit and feature tests using [Pest PHP](https://pestphp.com/), demonstrating how Middle's explicit design makes testing straightforward.

### Included Components

* **HTTP Foundation**: [Laminas Diactoros](https://docs.laminas.dev/laminas-diactoros/) for PSR-7 HTTP messages with [Laminas HTTP HandlerRunner](https://docs.laminas.dev/laminas-httphandlerrunner/) for response emission
* **Logging**: [Monolog](https://seldaek.github.io/monolog/) with proper error handling integration
* **Routing**: [Symfony Router](https://symfony.com/doc/current/routing.html) with Middle's routing abstraction
* **Database Migrations**: [Phinx](https://phinx.org/) for database schema management
* **Console Commands**: [Symfony Console](https://symfony.com/doc/current/components/console.html) for CLI functionality
* **JSON Support**: Automatic JSON request body parsing middleware
* **Testing**: [Pest PHP](https://pestphp.com/) with [Mockery](http://docs.mockery.io/) for elegant testing

### Demonstrated Patterns

* **Middleware Pipeline**: Explicit middleware stack construction in `ServiceContainer::getApp()`
* **Dependency Injection**: Interface-driven design with explicit container configuration
* **Error Handling**: Dedicated controllers for 404 and 500 responses
* **Routing Organization**: Clean separation using `RoutingProviderInterface`
* **Console Integration**: Command registration and service injection
* **Testing Strategy**: Both unit and feature test examples

## Installation

```bash
composer create-project jschreuder/middle-skeleton myapp dev-master
cd myapp
```

Set up the environment:

```bash
# Make logs directory writable
chmod 0755 var/logs

# Configure environment
cp config/env.php.dist config/env.php
cp config/dev.php.dist config/dev.php

# Edit config/dev.php with your database credentials and other settings
```

## Quick Start

**Test the Console Application:**
```bash
./console middle:example MyName
```

**Test the Web Application:**
```bash
# Start the development server
./console middle:webserver

# Or manually:
cd web && php -S localhost:8080
```

Visit `http://localhost:8080` - you should see a JSON "Hello World" message.

**Run the Test Suite:**
```bash
./vendor/bin/pest
```

## Application Structure

```
config/          # Environment-specific configuration
src/
├── Command/     # Console commands
├── Controller/  # HTTP request handlers
└── Service/     # Business logic services
tests/
├── Feature/     # Integration tests
└── Unit/        # Isolated unit tests
web/             # Web server document root
```

## Key Files

* **`config/app_init.php`**: Application bootstrapping, loads configuration and sets up dependency injection
* **`web/index.php`**: Web application entry point, processes HTTP requests through middleware pipeline
* **`console`**: CLI application entry point for running commands
* **`src/ServiceContainer.php`**: Dependency injection container with explicit service definitions
* **`src/GeneralRoutingProvider.php`**: Route definitions using Middle's routing abstraction

## Design Philosophy: Composition Over Framework Lock-in

Middle isn't designed to replace other frameworks - it's designed to let you **compose proven components on your terms**. Rather than accepting one framework's architectural decisions, you create your own interfaces and adapt mature libraries to fit your domain.

```php
// Your domain interface - exactly what your application needs
interface UserValidatorInterface 
{
    public function validateCreateUser(array $data): ValidationResult;
    public function validateUpdateUser(int $userId, array $data): ValidationResult;
}

// Adapter that wraps Symfony's complexity behind your interface
class SymfonyUserValidator implements UserValidatorInterface 
{
    public function __construct(private ValidatorInterface $symfonyValidator) {}
    
    public function validateCreateUser(array $data): ValidationResult 
    {
        // Transform your domain needs into Symfony validator calls
        $constraints = new Assert\Collection([
            'email' => new Assert\Email(),
            'name' => new Assert\NotBlank(),
        ]);
        
        $violations = $this->symfonyValidator->validate($data, $constraints);
        return ValidationResult::fromSymfonyViolations($violations);
    }
}

// Your application uses YOUR interface, not Symfony's
class CreateUserController implements ControllerInterface 
{
    public function __construct(private UserValidatorInterface $validator) {}
}
```

This approach gives you:
- **Library Independence**: Swap Symfony Validator for another library by implementing your interface
- **Domain Clarity**: Your interfaces reflect business needs, not library abstractions  
- **Future-Proof Evolution**: Library updates only require adapter changes, not application rewrites
- **Focused Testing**: Mock exactly what your application needs, not complex library interfaces

You get battle-tested components (for example the included Symfony Routing and Laminas Diactoros) with complete architectural control.

## Extending the Application

### Adding Routes

Routes are organized using routing providers:

```php
// In GeneralRoutingProvider::registerRoutes()
$router->get('users.list', '/users', function () {
    return new ListUsersController($this->container->getUserRepository());
});

$router->post('users.create', '/users', function () {
    return new CreateUserController($this->container->getUserRepository());
});
```

### Adding Middleware

Middleware is added explicitly to the application stack:

```php
// In ServiceContainer::getApp()
return new ApplicationStack(
    new ControllerRunner(),
    new JsonRequestParserMiddleware(),
    new SessionMiddleware($this->getSessionProcessor()), // New middleware
    new RoutingMiddleware($this->getAppRouter(), $this->get404Handler()),
    new ErrorHandlerMiddleware($this->getLogger(), $this->get500Handler())
);
```

### Scaling Your Application

As your application grows, Middle's explicit design supports modular organization at multiple levels:

**Within a Single Application:**
You can organize services using traits to keep the ServiceContainer manageable:

```php
trait DatabaseServices 
{
    public function getUserRepository(): UserRepository 
    {
        return new UserRepository($this->getDb());
    }
    
    public function getOrderRepository(): OrderRepository 
    {
        return new OrderRepository($this->getDb());
    }
}

trait ViewServices 
{
    public function getTwigRenderer(): TwigRenderer 
    {
        return new TwigRenderer($this->getTwig(), $this->getResponseFactory());
    }
}

class ServiceContainer 
{
    use ConfigTrait, DatabaseServices, ViewServices;
    
    // Core services remain here
    public function getApp(): ApplicationStack { ... }
    public function getLogger(): LoggerInterface { ... }
}
```

**Across Multiple Modules:**
For larger applications, consider splitting functionality into separate modules, each with their own repository. Each module can provide a service provider trait that integrates cleanly with your main application:

```php
// In your user-management module repository
trait UserModuleServices 
{
    public function getUserRepository(): UserRepository { ... }
    public function getUserController(): UserController { ... }
    public function getUserValidator(): UserValidator { ... }
}

// In your billing module repository  
trait BillingModuleServices 
{
    public function getInvoiceRepository(): InvoiceRepository { ... }
    public function getPaymentProcessor(): PaymentProcessor { ... }
    public function getBillingController(): BillingController { ... }
}

// In your main application's ServiceContainer
class ServiceContainer 
{
    use ConfigTrait, UserModuleServices, BillingModuleServices;
    
    // Application-level services
    public function getApp(): ApplicationStack { ... }
}
```

This approach maintains Middle's explicitness while being grounded in core PHP language concepts (traits, interfaces, namespaces), allowing you to:
- Develop and test modules independently
- Share modules across applications  
- Keep domain boundaries clear
- Scale team development across module ownership

Each module remains a focused, manageable unit while the main application composes them explicitly through the service container traits - no framework magic, just PHP.

### Adding Advanced Features

This skeleton provides a foundation - Middle Framework includes many additional features not configured here:

* **Request Validation & Filtering**: Automatic request processing using `RequestValidatorInterface` and `RequestFilterInterface`
* **Session Management**: Built-in session middleware with pluggable storage backends
* **Template Rendering**: View layer with Twig integration and response rendering
* **Advanced Error Handling**: HTTP-aware exceptions and custom error pages

For detailed examples and documentation of these features, see the [Middle Framework repository](https://github.com/jschreuder/Middle).

## Why Middle Framework?

Middle Framework prioritizes **long-term maintainability** over rapid prototyping. It's designed for applications that:

* Will be maintained by teams over time
* Need explicit, traceable request flow
* Require confidence when refactoring or extending functionality
* Benefit from interface-driven, testable architecture

If you prefer convention over configuration or need to prototype very quickly, Middle might not be the right choice. But if you want to build applications that remain pleasant to work with as they grow, Middle provides the foundation you need.

---

**Middle Framework: Explicit. Replaceable. Safe.**
