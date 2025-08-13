<?php
require_once __DIR__ . '/vendor/autoload.php';

use Payabli\PayabliClient;
use Payabli\Customer\Requests\AddCustomerRequest;
use Payabli\Types\CustomerData;
use Dotenv\Dotenv;

// Load environment variables
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

// Configuration
$apiKey = $_ENV['PAYABLI_KEY'] ?? '';
$entryPoint = $_ENV['PAYABLI_ENTRY'] ?? '';

// Debug: Log environment variables (mask API key for security)
error_log("API Key loaded: " . (empty($apiKey) ? 'NO' : 'YES (length: ' . strlen($apiKey) . ')'));
error_log("Entry Point loaded: " . (empty($entryPoint) ? 'NO' : $entryPoint));

if (empty($apiKey) || empty($entryPoint)) {
    die('PAYABLI_KEY and PAYABLI_ENTRY must be set in .env file');
}

// Initialize Payabli client
$payabliClient = new PayabliClient($apiKey);

// Handle routing based on request method and path
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove query string and normalize path
$requestUri = rtrim($requestUri, '/');
if (empty($requestUri)) {
    $requestUri = '/';
}

// Simple routing
switch ($requestUri) {
    case '/':
        if ($requestMethod === 'GET') {
            renderCreatePage();
        } else {
            http_response_code(405);
            echo 'Method Not Allowed';
        }
        break;
    
    case '/list':
        if ($requestMethod === 'GET') {
            renderListPage();
        } else {
            http_response_code(405);
            echo 'Method Not Allowed';
        }
        break;
    
    case '/api/create':
        if ($requestMethod === 'POST') {
            handleCreateCustomer($payabliClient, $entryPoint);
        } else {
            http_response_code(405);
            echo 'Method Not Allowed';
        }
        break;
    
    case '/api/list':
        if ($requestMethod === 'GET') {
            handleListCustomers($payabliClient, $entryPoint);
        } else {
            http_response_code(405);
            echo 'Method Not Allowed';
        }
        break;
    
    default:
        // Handle delete customer route with pattern /api/delete/{customerId}
        if (preg_match('/^\/api\/delete\/(\d+)$/', $requestUri, $matches)) {
            if ($requestMethod === 'DELETE') {
                $customerId = (int)$matches[1];
                handleDeleteCustomer($payabliClient, $customerId);
            } else {
                http_response_code(405);
                echo 'Method Not Allowed';
            }
        } else {
            http_response_code(404);
            echo '404 Not Found';
        }
        break;
}

function renderLayout($title, $content) {
    echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>$title - Payabli SDK Example</title>
    
    <!-- Using Pico CSS for styling -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    
    <!-- Using HTMX for AJAX requests -->
    <script src="https://unpkg.com/htmx.org@2.0.4"></script>
</head>
<body>
    <main class="container">
        <nav>
            <ul>
                <li><strong>Payabli SDK Test</strong></li>
            </ul>
            <ul hx-boost="true">
                <li><a href="/">Create Customer</a></li>
                <li><a href="/list">List Customers</a></li>
            </ul>
        </nav>
    </main>
    $content
</body>
</html>
HTML;
}

function renderCreatePage() {
    $content = <<<HTML
<main class="container">
    <article>
        <header>
            <em><b>Create Customer</b></em>
        </header>
        <form hx-post="/api/create" hx-target="#form-result" hx-swap="innerHTML">
            <fieldset>
                <div class="grid">
                    <label>
                        First name
                        <input
                            name="firstname"
                            placeholder="John"
                            autocomplete="given-name"
                            aria-describedby="first_name-description"
                            required
                        />
                        <small id="first_name-description">
                            Your first name is used to personalize your experience.
                        </small>
                    </label>
                    <label>
                        Last name
                        <input
                            name="lastname"
                            placeholder="Doe"
                            autocomplete="family-name"
                            aria-describedby="last_name-description"
                            required
                        />
                        <small id="last_name-description">
                            Your last name is used for identification purposes.
                        </small>
                    </label>
                </div>
                <div class="grid">
                    <label>
                        Email
                        <input
                            type="email"
                            name="email"
                            placeholder="Email"
                            autocomplete="email"
                            aria-describedby="email-description"
                            required
                        />
                        <small id="email-description">
                            We'll never share your email with anyone else.
                        </small>
                    </label>
                    <label>
                        Timezone
                        <select 
                            name="timeZone" 
                            aria-label="Select your timezone..." 
                            aria-describedby="timezone-description" 
                            required
                        >
                            <option selected disabled value="">
                                Select your timezone...
                            </option>
                            <option value="-5">UTC-05:00 Eastern Time (US & Canada)</option>
                            <option value="-6">UTC-06:00 Central Time (US & Canada)</option>
                            <option value="-7">UTC-07:00 Mountain Time (US & Canada)</option>
                            <option value="-8">UTC-08:00 Pacific Time (US & Canada)</option>
                        </select>
                        <small id="timezone-description">
                            Your timezone is used to display transaction times correctly. 
                        </small>
                    </label>
                </div>
                <div class="grid">
                    <label>
                        Address
                        <input
                            name="address"
                            placeholder="123 Bishop's Trail"
                            aria-describedby="address-description"
                            required
                        />
                        <small id="address-description">
                            Your address is used for billing purposes.
                        </small>
                    </label>
                    <label>
                        City
                        <input
                            name="city"
                            placeholder="Mountain City"
                            aria-describedby="city-description"
                            required
                        />
                        <small id="city-description">
                            Your city is used for billing purposes.
                        </small>
                    </label>
                </div>
                <div class="grid">
                    <label>
                        State
                        <input
                            name="state"
                            placeholder="TN"
                            aria-describedby="state-description"
                            required
                        />
                        <small id="state-description">
                            Your state is used for billing purposes.
                        </small>
                    </label>
                    <label>
                        Zip
                        <input
                            name="zip"
                            placeholder="37612"
                            aria-describedby="zip-description"
                            required
                        />
                        <small id="zip-description">
                            Your zip code is used for billing purposes.
                        </small>
                    </label>
                </div>
                <div class="grid">
                    <div>
                        <fieldset>
                            <legend>Country</legend>
                            <input type="radio" id="us" name="country" value="US" checked />
                            <label for="us">US</label>
                            <input type="radio" id="ca" name="country" value="CA" />
                            <label for="ca">CA</label>
                        </fieldset>
                        <small>
                            Your country affects the currency and payment methods available to you.
                        </small>
                    </div>
                    <div>
                        <fieldset>
                            <legend>Services</legend>
                            <input type="checkbox" id="hvac" name="hvac" value="on" />
                            <label for="hvac">HVAC</label>
                            <input type="checkbox" id="electrical" name="electrical" value="on" />
                            <label for="electrical">Electrical</label>
                        </fieldset>
                        <small>
                            This helps us tailor our offerings to your needs.
                        </small>
                    </div>
                </div>
                <hr/>
                <label>
                    <input name="terms" type="checkbox" role="switch" required />
                    I agree to the <a href="#">terms and conditions</a>
                </label>
            </fieldset>
            <input
                type="submit"
                value="Create"
            />
        </form>
    </article>
    <h1 id="form-result"></h1>
</main>
HTML;
    
    renderLayout('Create Customer', $content);
}

function renderListPage() {
    $content = <<<HTML
<main class="container">
    <article>
        <header>
            <em><b>Customer List</b></em>
        </header>
        <table hx-get="/api/list" hx-swap="outerHTML" hx-trigger="load" hx-indicator="#spinner"></table>
    </article>
</main>
<div id="spinner" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 300px; height: 300px; display: flex; justify-content: center; align-items: center;" class="htmx-indicator">
    <svg width="240" height="240" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><style>.spinner_ajPY{transform-origin:center;animation:spinner_AtaB .75s infinite linear}@keyframes spinner_AtaB{100%{transform:rotate(360deg)}}</style><path d="M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm0,19a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z" opacity=".25"/><path d="M10.14,1.16a11,11,0,0,0-9,8.92A1.59,1.59,0,0,0,2.46,12,1.52,1.52,0,0,0,4.11,10.7a8,8,0,0,1,6.66-6.61A1.42,1.42,0,0,0,12,2.69h0A1.57,1.57,0,0,0,10.14,1.16Z" class="spinner_ajPY"/></svg>
</div>
HTML;
    
    renderLayout('Customer List', $content);
}

function handleCreateCustomer($payabliClient, $entryPoint) {
    try {
        // Debug: Log all received POST data
        error_log("POST data received: " . json_encode($_POST));
        error_log("Entry point: " . $entryPoint);
        
        // Parse form data
        $firstname = $_POST['firstname'] ?? '';
        $lastname = $_POST['lastname'] ?? '';
        $email = $_POST['email'] ?? '';
        $timeZone = (int)($_POST['timeZone'] ?? 0);
        $address = $_POST['address'] ?? '';
        $city = $_POST['city'] ?? '';
        $state = $_POST['state'] ?? '';
        $zip = $_POST['zip'] ?? '';
        $country = $_POST['country'] ?? '';
        $hvac = $_POST['hvac'] ?? null;
        $electrical = $_POST['electrical'] ?? null;
        
        // Prepare additional fields
        $additionalFields = [];
        if ($hvac) {
            $additionalFields['hvac'] = $hvac;
        }
        if ($electrical) {
            $additionalFields['electrical'] = $electrical;
        }
        
        // Create customer data
        $customerData = new CustomerData([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'timeZone' => $timeZone,
            'address' => $address,
            'city' => $city,
            'state' => $state,
            'zip' => $zip,
            'country' => $country,
            'additionalFields' => !empty($additionalFields) ? $additionalFields : null,
            'identifierFields' => ['email']  // Required field
        ]);
        
        // Debug: Log customer data
        error_log("Customer data prepared: " . json_encode($customerData));
        
        // Create the request
        $request = new AddCustomerRequest([
            'body' => $customerData,
        ]);
        
        // Debug: Log request
        error_log("Request prepared: " . json_encode($request));
        
        // Call the Payabli API
        $result = $payabliClient->customer->addCustomer($entryPoint, $request);
        
        error_log("Customer created successfully: " . json_encode($result));
        
        http_response_code(201);
        header('Content-Type: text/html');
        echo '<input type="text" name="valid" value="Success!" aria-invalid="false" id="form-result" readonly>';
        
    } catch (\Payabli\Exceptions\PayabliApiException $e) {
        error_log("Payabli API Exception: " . $e->getMessage());
        error_log("Response Body: " . $e->getBody());
        
        http_response_code(200);
        header('Content-Type: text/html');
        header('HX-Reswap: innerHTML');
        echo '<input type="text" name="invalid" value="API Error: ' . htmlspecialchars($e->getMessage()) . '" aria-invalid="true" id="form-result" readonly>';
        
    } catch (\Payabli\Exceptions\PayabliException $e) {
        error_log("Payabli Exception: " . $e->getMessage());
        error_log("Previous: " . ($e->getPrevious() ? $e->getPrevious()->getMessage() : 'None'));
        
        http_response_code(200);
        header('Content-Type: text/html');
        header('HX-Reswap: innerHTML');
        echo '<input type="text" name="invalid" value="SDK Error: ' . htmlspecialchars($e->getMessage()) . '" aria-invalid="true" id="form-result" readonly>';
        
    } catch (Exception $e) {
        error_log("General Exception: " . $e->getMessage());
        error_log("Stack trace: " . $e->getTraceAsString());
        
        http_response_code(200);
        header('Content-Type: text/html');
        header('HX-Reswap: innerHTML');
        echo '<input type="text" name="invalid" value="Error: ' . htmlspecialchars($e->getMessage()) . '" aria-invalid="true" id="form-result" readonly>';
    }
}

function handleListCustomers($payabliClient, $entryPoint) {
    try {
        $result = $payabliClient->query->listCustomers($entryPoint);
        
        // Build table rows
        $tableRows = '';
        if (isset($result->records) && is_array($result->records) && !empty($result->records)) {
            foreach ($result->records as $record) {
                // Use safe property access for customer data
                $firstname = property_exists($record, 'firstname') ? $record->firstname : '';
                $lastname = property_exists($record, 'lastname') ? $record->lastname : '';
                $email = property_exists($record, 'email') ? $record->email : '';
                $address = property_exists($record, 'address') ? $record->address : '';
                $city = property_exists($record, 'city') ? $record->city : '';
                $state = property_exists($record, 'state') ? $record->state : '';
                $zip = property_exists($record, 'zip') ? $record->zip : '';
                $timeZone = property_exists($record, 'timeZone') ? $record->timeZone : '';
                $customerId = property_exists($record, 'customerId') ? $record->customerId : '';
                
                $tableRows .= <<<HTML
                <tr>
                    <td>{$firstname}</td>
                    <td>{$lastname}</td>
                    <td>{$email}</td>
                    <td>{$address}</td>
                    <td>{$city}</td>
                    <td>{$state}</td>
                    <td>{$zip}</td>
                    <td>{$timeZone}</td>
                    <td>
                        <button id="delete" class="outline"
                            hx-delete="/api/delete/{$customerId}" 
                            hx-swap="innerHTML" 
                            hx-target="closest tr"  
                            hx-on="htmx:beforeRequest: this.setAttribute('aria-busy', 'true'), htmx:afterRequest: this.removeAttribute('aria-busy')"
                        >
                            ❌
                        </button>
                    </td>
                </tr>
HTML;
            }
        } else {
            $tableRows = <<<HTML
                <tr>
                    <td colspan="9" style="text-align: center; color: #666;">
                        No customers found. Create a customer to get started!
                    </td>
                </tr>
HTML;
        }
        
        $table = <<<HTML
        <table class="striped">
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Zip</th>
                    <th>Time Zone</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                {$tableRows}
            </tbody>
        </table>
HTML;
        
        header('Content-Type: text/html');
        echo $table;
        
    } catch (Exception $e) {
        error_log("Error listing customers: " . $e->getMessage());
        
        http_response_code(500);
        header('Content-Type: text/html');
        echo '<p>Error loading customers. Please check your API credentials and try again.</p>';
    }
}

function handleDeleteCustomer($payabliClient, $customerId) {
    try {
        $result = $payabliClient->customer->deleteCustomer($customerId);
        error_log("Customer deleted: " . json_encode($result));
        
        header('Content-Type: text/html');
        echo '';
        
    } catch (Exception $e) {
        error_log("Error deleting customer: " . $e->getMessage());
        
        http_response_code(500);
        header('Content-Type: text/html');
        echo '<td colspan="9">Error deleting customer: ' . htmlspecialchars($e->getMessage()) . '</td>';
    }
}