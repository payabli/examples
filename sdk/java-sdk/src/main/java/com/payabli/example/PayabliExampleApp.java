package com.payabli.example;

import com.payabli.api.PayabliApiClient;
import com.payabli.api.PayabliApiClientBuilder;
import com.payabli.api.resources.customer.requests.AddCustomerRequest;
import com.payabli.api.types.CustomerData;
import com.payabli.api.types.QueryCustomerResponse;
import com.payabli.api.types.CustomerQueryRecords;
import com.payabli.api.types.PayabliApiResponseCustomerQuery;
import io.javalin.Javalin;
import io.javalin.http.Context;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import java.util.*;

public class PayabliExampleApp {
    private static final Logger logger = LoggerFactory.getLogger(PayabliExampleApp.class);
    private static PayabliApiClient payabliClient;
    private static String entryPoint;

    public static void main(String[] args) {
        // Load environment variables
        String apiKey = System.getenv("PAYABLI_KEY");
        entryPoint = System.getenv("PAYABLI_ENTRY");

        if (apiKey == null || entryPoint == null) {
            logger.error("PAYABLI_KEY and PAYABLI_ENTRY environment variables must be set");
            System.exit(1);
        }

        // Initialize Payabli client
        payabliClient = new PayabliApiClientBuilder()
                .apiKey(apiKey)
                .build();

        // Create and configure Javalin app
        Javalin app = Javalin.create(config -> {
            config.staticFiles.add("static");
        });

        // Routes
        app.get("/", PayabliExampleApp::renderCreateCustomerPage);
        app.get("/list", PayabliExampleApp::renderListCustomersPage);
        app.post("/api/create", PayabliExampleApp::createCustomer);
        app.get("/api/list", PayabliExampleApp::listCustomers);
        app.delete("/api/delete/{customerId}", PayabliExampleApp::deleteCustomer);

        // Start server
        int port = Integer.parseInt(System.getProperty("server.port", "8000"));
        app.start(port);
        
        logger.info("Payabli SDK Example app started on http://localhost:{}", port);
    }

    private static void renderCreateCustomerPage(Context ctx) {
        String html = getCreateCustomerPageHtml();
        ctx.html(html);
    }

    private static void renderListCustomersPage(Context ctx) {
        String html = getListCustomersPageHtml();
        ctx.html(html);
    }

    private static String getCreateCustomerPageHtml() {
        return "<!DOCTYPE html>" +
                "<html lang=\"en\">" +
                "<head>" +
                "    <meta charset=\"utf-8\" />" +
                "    <link rel=\"icon\" type=\"image/svg+xml\" href=\"/favicon.svg\" />" +
                "    <link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css\">" +
                "    <script src=\"https://unpkg.com/htmx.org@2.0.4\"></script>" +
                "    <meta name=\"viewport\" content=\"width=device-width\" />" +
                "    <title>Payabli SDK Example</title>" +
                "</head>" +
                "<body>" +
                "    <main class=\"container\">" +
                "        <nav>" +
                "            <ul>" +
                "                <li><strong>Payabli SDK Test</strong></li>" +
                "            </ul>" +
                "            <ul hx-boost=\"true\">" +
                "                <li><a href=\"/\">Create Customer</a></li>" +
                "                <li><a href=\"/list\">List Customers</a></li>" +
                "            </ul>" +
                "        </nav>" +
                "    </main>" +
                "    <main class=\"container\">" +
                "        <article>" +
                "            <header>" +
                "                <em><b>Create Customer</b></em>" +
                "            </header>" +
                "            <form hx-post=\"/api/create\" hx-target=\"#form-result\" hx-swap=\"innerHTML\">" +
                "                <fieldset>" +
                "                    <div class=\"grid\">" +
                "                        <label>" +
                "                            First name" +
                "                            <input name=\"firstname\" placeholder=\"John\" autocomplete=\"given-name\" aria-describedby=\"first_name-description\" required />" +
                "                            <small id=\"first_name-description\">Your first name is used to personalize your experience.</small>" +
                "                        </label>" +
                "                        <label>" +
                "                            Last name" +
                "                            <input name=\"lastname\" placeholder=\"Doe\" autocomplete=\"family-name\" aria-describedby=\"last_name-description\" required />" +
                "                            <small id=\"last_name-description\">Your last name is used for identification purposes.</small>" +
                "                        </label>" +
                "                    </div>" +
                "                    <div class=\"grid\">" +
                "                        <label>" +
                "                            Email" +
                "                            <input type=\"email\" name=\"email\" placeholder=\"Email\" autocomplete=\"email\" aria-describedby=\"email-description\" required />" +
                "                            <small id=\"email-description\">We'll never share your email with anyone else.</small>" +
                "                        </label>" +
                "                        <label>" +
                "                            Timezone" +
                "                            <select name=\"timeZone\" aria-label=\"Select your timezone...\" aria-describedby=\"timezone-description\" required>" +
                "                                <option selected disabled value=\"\">Select your timezone...</option>" +
                "                                <option value=\"-5\">UTC-05:00 Eastern Time (US & Canada)</option>" +
                "                                <option value=\"-6\">UTC-06:00 Central Time (US & Canada)</option>" +
                "                                <option value=\"-7\">UTC-07:00 Mountain Time (US & Canada)</option>" +
                "                                <option value=\"-8\">UTC-08:00 Pacific Time (US & Canada)</option>" +
                "                            </select>" +
                "                            <small id=\"timezone-description\">Your timezone is used to display transaction times correctly.</small>" +
                "                        </label>" +
                "                    </div>" +
                "                    <div class=\"grid\">" +
                "                        <label>" +
                "                            Address" +
                "                            <input name=\"address\" placeholder=\"123 Bishop's Trail\" aria-describedby=\"address-description\" required />" +
                "                            <small id=\"address-description\">Your address is used for billing purposes.</small>" +
                "                        </label>" +
                "                        <label>" +
                "                            City" +
                "                            <input name=\"city\" placeholder=\"Mountain City\" aria-describedby=\"city-description\" required />" +
                "                            <small id=\"city-description\">Your city is used for billing purposes.</small>" +
                "                        </label>" +
                "                    </div>" +
                "                    <div class=\"grid\">" +
                "                        <label>" +
                "                            State" +
                "                            <input name=\"state\" placeholder=\"TN\" aria-describedby=\"state-description\" required />" +
                "                            <small id=\"state-description\">Your state is used for billing purposes.</small>" +
                "                        </label>" +
                "                        <label>" +
                "                            Zip" +
                "                            <input name=\"zip\" placeholder=\"37612\" aria-describedby=\"zip-description\" required />" +
                "                            <small id=\"zip-description\">Your zip code is used for billing purposes.</small>" +
                "                        </label>" +
                "                    </div>" +
                "                    <div class=\"grid\">" +
                "                        <div>" +
                "                            <fieldset>" +
                "                                <legend>Country</legend>" +
                "                                <input type=\"radio\" id=\"us\" name=\"country\" value=\"us\" checked />" +
                "                                <label for=\"us\">US</label>" +
                "                                <input type=\"radio\" id=\"ca\" name=\"country\" value=\"ca\" />" +
                "                                <label for=\"ca\">CA</label>" +
                "                            </fieldset>" +
                "                            <small>Your country affects the currency and payment methods available to you.</small>" +
                "                        </div>" +
                "                        <div>" +
                "                            <fieldset>" +
                "                                <legend>Services</legend>" +
                "                                <input type=\"checkbox\" id=\"hvac\" name=\"hvac\" value=\"on\" />" +
                "                                <label for=\"hvac\">HVAC</label>" +
                "                                <input type=\"checkbox\" id=\"electrical\" name=\"electrical\" value=\"on\" />" +
                "                                <label for=\"electrical\">Electrical</label>" +
                "                            </fieldset>" +
                "                            <small>This helps us tailor our offerings to your needs.</small>" +
                "                        </div>" +
                "                    </div>" +
                "                    <hr/>" +
                "                    <label>" +
                "                        <input name=\"terms\" type=\"checkbox\" role=\"switch\" required />" +
                "                        I agree to the <a href=\"#\">terms and conditions</a>" +
                "                    </label>" +
                "                </fieldset>" +
                "                <input type=\"submit\" value=\"Create\" />" +
                "            </form>" +
                "        </article>" +
                "        <h1 id=\"form-result\"></h1>" +
                "    </main>" +
                "</body>" +
                "</html>";
    }

    private static String getListCustomersPageHtml() {
        return "<!DOCTYPE html>" +
                "<html lang=\"en\">" +
                "<head>" +
                "    <meta charset=\"utf-8\" />" +
                "    <link rel=\"icon\" type=\"image/svg+xml\" href=\"/favicon.svg\" />" +
                "    <link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css\">" +
                "    <script src=\"https://unpkg.com/htmx.org@2.0.4\"></script>" +
                "    <meta name=\"viewport\" content=\"width=device-width\" />" +
                "    <title>Payabli SDK Example</title>" +
                "</head>" +
                "<body>" +
                "    <main class=\"container\">" +
                "        <nav>" +
                "            <ul>" +
                "                <li><strong>Payabli SDK Test</strong></li>" +
                "            </ul>" +
                "            <ul hx-boost=\"true\">" +
                "                <li><a href=\"/\">Create Customer</a></li>" +
                "                <li><a href=\"/list\">List Customers</a></li>" +
                "            </ul>" +
                "        </nav>" +
                "    </main>" +
                "    <main class=\"container\">" +
                "        <article>" +
                "            <header>" +
                "                <em><b>Customer List</b></em>" +
                "            </header>" +
                "            <table hx-get=\"/api/list\" hx-swap=\"outerHTML\" hx-trigger=\"load\" hx-indicator=\"#spinner\"></table>" +
                "        </article>" +
                "    </main>" +
                "    <div id=\"spinner\" style=\"position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 300px; height: 300px; display: flex; justify-content: center; align-items: center;\" class=\"htmx-indicator\">" +
                "        <svg width=\"240\" height=\"240\" viewBox=\"0 0 24 24\" xmlns=\"http://www.w3.org/2000/svg\">" +
                "            <style>.spinner_ajPY{transform-origin:center;animation:spinner_AtaB .75s infinite linear}@keyframes spinner_AtaB{100%{transform:rotate(360deg)}}</style>" +
                "            <path d=\"M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm0,19a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z\" opacity=\".25\"/>" +
                "            <path d=\"M10.14,1.16a11,11,0,0,0-9,8.92A1.59,1.59,0,0,0,2.46,12,1.52,1.52,0,0,0,4.11,10.7a8,8,0,0,1,6.66-6.61A1.42,1.42,0,0,0,12,2.69h0A1.57,1.57,0,0,0,10.14,1.16Z\" class=\"spinner_ajPY\"/>" +
                "        </svg>" +
                "    </div>" +
                "</body>" +
                "</html>";
    }

    private static void createCustomer(Context ctx) {
        try {
            // Extract form parameters
            String firstname = ctx.formParam("firstname");
            String lastname = ctx.formParam("lastname");
            String email = ctx.formParam("email");
            String timeZoneStr = ctx.formParam("timeZone");
            String address = ctx.formParam("address");
            String city = ctx.formParam("city");
            String state = ctx.formParam("state");
            String zip = ctx.formParam("zip");
            String country = ctx.formParam("country");
            String hvac = ctx.formParam("hvac");
            String electrical = ctx.formParam("electrical");

            // Prepare additional fields
            Map<String, Optional<String>> additionalFields = new HashMap<>();
            if (hvac != null && !hvac.isEmpty()) {
                additionalFields.put("hvac", Optional.of(hvac));
            }
            if (electrical != null && !electrical.isEmpty()) {
                additionalFields.put("electrical", Optional.of(electrical));
            }

            // Create customer data
            CustomerData customerData = CustomerData.builder()
                    .firstname(firstname)
                    .lastname(lastname)
                    .email(email)
                    .timeZone(Integer.parseInt(timeZoneStr))
                    .address(address)
                    .city(city)
                    .state(state)
                    .zip(zip)
                    .country(country)
                    .additionalFields(additionalFields.isEmpty() ? Optional.empty() : Optional.of(additionalFields))
                    .identifierFields(Optional.of(Arrays.asList(Optional.of("email"))))
                    .build();

            // Create the request
            AddCustomerRequest request = AddCustomerRequest.builder()
                    .body(customerData)
                    .forceCustomerCreation(true)
                    .build();

            // Call the Payabli API
            PayabliApiResponseCustomerQuery result = payabliClient.customer().addCustomer(entryPoint, request);
            
            logger.info("Customer created successfully: {}", result);
            
            ctx.html("<input type=\"text\" name=\"valid\" value=\"Success!\" aria-invalid=\"false\" id=\"form-result\" readonly>")
               .status(201);
               
        } catch (Exception e) {
            logger.error("Error creating customer: {}", e.getMessage(), e);
            ctx.html("<input type=\"text\" name=\"invalid\" value=\"Error!\" aria-invalid=\"true\" id=\"form-result\" readonly>")
               .header("HX-Reswap", "innerHTML")
               .status(200);
        }
    }

    private static void listCustomers(Context ctx) {
        try {
            QueryCustomerResponse result = payabliClient.query().listCustomers(entryPoint);
            
            StringBuilder tableRows = new StringBuilder();
            
            if (result.getRecords().isPresent() && !result.getRecords().get().isEmpty()) {
                for (CustomerQueryRecords record : result.getRecords().get()) {
                    String firstname = record.getFirstname().orElse("");
                    String lastname = record.getLastname().orElse("");
                    String email = record.getEmail().orElse("");
                    String address = record.getAddress().orElse("");
                    String city = record.getCity().orElse("");
                    String state = record.getState().orElse("");
                    String zip = record.getZip().orElse("");
                    String timeZone = record.getTimeZone().map(String::valueOf).orElse("");
                    String customerId = record.getCustomerId().map(String::valueOf).orElse("");
                    
                    tableRows.append("<tr>")
                            .append("<td>").append(escapeHtml(firstname)).append("</td>")
                            .append("<td>").append(escapeHtml(lastname)).append("</td>")
                            .append("<td>").append(escapeHtml(email)).append("</td>")
                            .append("<td>").append(escapeHtml(address)).append("</td>")
                            .append("<td>").append(escapeHtml(city)).append("</td>")
                            .append("<td>").append(escapeHtml(state)).append("</td>")
                            .append("<td>").append(escapeHtml(zip)).append("</td>")
                            .append("<td>").append(escapeHtml(timeZone)).append("</td>")
                            .append("<td>")
                            .append("<button id=\"delete\" class=\"outline\"")
                            .append(" hx-delete=\"/api/delete/").append(customerId).append("\"")
                            .append(" hx-swap=\"innerHTML\"")
                            .append(" hx-target=\"closest tr\"")
                            .append(" hx-on=\"htmx:beforeRequest: this.setAttribute('aria-busy', 'true'), htmx:afterRequest: this.removeAttribute('aria-busy')\">")
                            .append("X")
                            .append("</button>")
                            .append("</td>")
                            .append("</tr>");
                }
            } else {
                tableRows.append("<tr>")
                        .append("<td colspan=\"9\" style=\"text-align: center; color: #666;\">")
                        .append("No customers found. Create a customer to get started!")
                        .append("</td>")
                        .append("</tr>");
            }
            
            String table = "<table class=\"striped\">" +
                    "<thead>" +
                    "<tr>" +
                    "<th>First Name</th>" +
                    "<th>Last Name</th>" +
                    "<th>Email</th>" +
                    "<th>Address</th>" +
                    "<th>City</th>" +
                    "<th>State</th>" +
                    "<th>Zip</th>" +
                    "<th>Time Zone</th>" +
                    "<th></th>" +
                    "</tr>" +
                    "</thead>" +
                    "<tbody>" +
                    tableRows.toString() +
                    "</tbody>" +
                    "</table>";
            
            ctx.html(table);
            
        } catch (Exception e) {
            logger.error("Error listing customers: {}", e.getMessage(), e);
            ctx.html("<p>Error loading customers. Please check your API credentials and try again.</p>")
               .status(500);
        }
    }

    private static void deleteCustomer(Context ctx) {
        try {
            String customerIdStr = ctx.pathParam("customerId");
            long customerId = Long.parseLong(customerIdStr);
            
            payabliClient.customer().deleteCustomer((int)customerId);
            logger.info("Customer deleted: {}", customerId);
            
            ctx.html("").status(200);
            
        } catch (NumberFormatException e) {
            logger.error("Invalid customer ID format: {}", ctx.pathParam("customerId"));
            ctx.html("<td colspan=\"9\">Invalid customer ID</td>").status(400);
        } catch (Exception e) {
            logger.error("Error deleting customer: {}", e.getMessage(), e);
            ctx.html("<td colspan=\"9\">Error deleting customer: " + e.getMessage() + "</td>").status(500);
        }
    }

    private static String escapeHtml(String input) {
        if (input == null) return "";
        return input
                .replace("&", "&amp;")
                .replace("<", "&lt;")
                .replace(">", "&gt;")
                .replace("\"", "&quot;")
                .replace("'", "&#x27;");
    }
}
