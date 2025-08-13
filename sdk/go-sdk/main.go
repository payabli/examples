package main

import (
	"context"
	"fmt"
	"html/template"
	"log"
	"net/http"
	"os"
	"strconv"
	"strings"

	api "sdk" // Import the Payabli Go SDK
	"sdk/client"
	"sdk/option"

	"github.com/gorilla/mux"
	"github.com/joho/godotenv"
)

var (
	payabliClient *client.Client
	entryPoint    string
	templates     *template.Template
)

func init() {
	// Load environment variables
	if err := godotenv.Load(); err != nil {
		log.Println("Warning: .env file not found, using system environment variables")
	}

	apiKey := os.Getenv("PAYABLI_KEY")
	entryPoint = os.Getenv("PAYABLI_ENTRY")

	if apiKey == "" || entryPoint == "" {
		log.Fatal("PAYABLI_KEY and PAYABLI_ENTRY must be set in environment variables")
	}

	// Initialize Payabli client
	payabliClient = client.NewClient(
		option.WithApiKey(apiKey),
		option.WithBaseURL(api.Environments.Sandbox), // Use sandbox environment
	)

	// Parse templates
	var err error
	templates, err = template.ParseGlob("templates/*.html")
	if err != nil {
		log.Fatal("Error parsing templates:", err)
	}
}

func main() {
	r := mux.NewRouter()

	// Serve static files
	r.PathPrefix("/static/").Handler(http.StripPrefix("/static/", http.FileServer(http.Dir("static/"))))

	// Routes
	r.HandleFunc("/", createCustomerPage).Methods("GET")
	r.HandleFunc("/list", listCustomersPage).Methods("GET")
	r.HandleFunc("/api/create", createCustomerAPI).Methods("POST")
	r.HandleFunc("/api/list", listCustomersAPI).Methods("GET")
	r.HandleFunc("/api/delete/{customerId}", deleteCustomerAPI).Methods("DELETE")

	// Add CORS headers for HTMX requests
	r.Use(corsMiddleware)

	fmt.Println("Server starting on :8080")
	fmt.Println("Visit http://localhost:8080 to view the application")
	log.Fatal(http.ListenAndServe(":8080", r))
}

func corsMiddleware(next http.Handler) http.Handler {
	return http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
		w.Header().Set("Access-Control-Allow-Origin", "*")
		w.Header().Set("Access-Control-Allow-Methods", "GET, POST, PUT, DELETE, OPTIONS")
		w.Header().Set("Access-Control-Allow-Headers", "Content-Type, HX-Request, HX-Target, HX-Current-URL")

		if r.Method == "OPTIONS" {
			w.WriteHeader(http.StatusOK)
			return
		}

		next.ServeHTTP(w, r)
	})
}

func createCustomerPage(w http.ResponseWriter, r *http.Request) {
	err := templates.ExecuteTemplate(w, "create.html", nil)
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		log.Printf("Template execution error: %v", err)
	}
}

func listCustomersPage(w http.ResponseWriter, r *http.Request) {
	err := templates.ExecuteTemplate(w, "list.html", nil)
	if err != nil {
		http.Error(w, err.Error(), http.StatusInternalServerError)
		log.Printf("Template execution error: %v", err)
	}
}

func createCustomerAPI(w http.ResponseWriter, r *http.Request) {
	// Parse form data
	err := r.ParseForm()
	if err != nil {
		http.Error(w, "Error parsing form data", http.StatusBadRequest)
		return
	}

	// Extract form fields
	firstname := r.FormValue("firstname")
	lastname := r.FormValue("lastname")
	email := r.FormValue("email")
	timeZone := r.FormValue("timeZone")
	address := r.FormValue("address")
	city := r.FormValue("city")
	state := r.FormValue("state")
	zip := r.FormValue("zip")
	country := r.FormValue("country")
	hvac := r.FormValue("hvac")
	electrical := r.FormValue("electrical")

	// Convert timezone to integer
	timeZoneInt, err := strconv.Atoi(timeZone)
	if err != nil {
		log.Printf("Invalid timezone value: %s", timeZone)
		w.Header().Set("Content-Type", "text/html")
		w.WriteHeader(http.StatusOK)
		w.Write([]byte(`<input type="text" name="invalid" value="Error!" aria-invalid="true" id="form-result" readonly>`))
		return
	}

	// Prepare additional fields
	additionalFields := make(map[string]*string)
	if hvac != "" {
		additionalFields["hvac"] = &hvac
	}
	if electrical != "" {
		additionalFields["electrical"] = &electrical
	}

	ctx := context.Background()

	// Convert entry point to proper type
	entry := api.Entrypointfield(entryPoint)

	// Create customer request matching the TypeScript SDK structure
	ptr_email := &email
	ptr_timeZone := &timeZoneInt

	customerRequest := &api.AddCustomerRequest{
		ForceCustomerCreation: api.Bool(true),
		Body: &api.CustomerData{
			Firstname:        api.String(firstname),
			Lastname:         api.String(lastname),
			Email:            &ptr_email,
			Zip:              api.String(zip),
			TimeZone:         &ptr_timeZone,
			Country:          api.String(country),
			State:            api.String(state),
			City:             api.String(city),
			Address:          api.String(address),
			AdditionalFields: additionalFields,
		},
	}

	// Call the Payabli API
	result, err := payabliClient.Customer.AddCustomer(ctx, entry, customerRequest)
	if err != nil {
		log.Printf("API Error: %v", err)
		w.Header().Set("Content-Type", "text/html")
		w.Header().Set("HX-Reswap", "innerHTML")
		w.WriteHeader(http.StatusOK)
		w.Write([]byte(`<input type="text" name="invalid" value="Error!" aria-invalid="true" id="form-result" readonly>`))
		return
	}

	log.Printf("Customer created successfully: %+v", result)
	w.Header().Set("Content-Type", "text/html")
	w.WriteHeader(http.StatusCreated)
	w.Write([]byte(`<input type="text" name="valid" value="Success!" aria-invalid="false" id="form-result" readonly>`))
}

func listCustomersAPI(w http.ResponseWriter, r *http.Request) {
	ctx := context.Background()
	entry := api.Entry(entryPoint)

	// Call the list customers API
	result, err := payabliClient.Query.ListCustomers(ctx, entry, &api.ListCustomersRequest{})
	if err != nil {
		log.Printf("Error listing customers: %v", err)
		w.Header().Set("Content-Type", "text/html")
		w.WriteHeader(http.StatusInternalServerError)
		w.Write([]byte(`<p>Error loading customers. Please check your API credentials and try again.</p>`))
		return
	}

	// Build table rows - match TypeScript version structure
	var tableRows strings.Builder

	if len(result.Records) > 0 {
		for _, record := range result.Records {
			// Use TypeScript field naming convention (capitalized)
			firstname := safeStringValue(record.Firstname)
			lastname := safeStringValue(record.Lastname)
			email := safeStringValue(*record.Email)
			address := safeStringValue(record.Address)
			city := safeStringValue(record.City)
			state := safeStringValue(record.State)
			zipCode := safeStringValue(record.Zip)
			timeZone := safeIntValue(*record.TimeZone)
			// CustomerId type is *int64
			customerId := **record.CustomerId

			tableRows.WriteString(fmt.Sprintf(`
				<tr>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td>%d</td>
					<td>
						<button id="delete" class="outline"
							hx-delete="/api/delete/%d" 
							hx-swap="innerHTML" 
							hx-target="closest tr"  
							hx-on="htmx:beforeRequest: this.setAttribute('aria-busy', 'true'), htmx:afterRequest: this.removeAttribute('aria-busy')"
						>
							❌
						</button>
					</td>
				</tr>
			`, firstname, lastname, email, address, city, state, zipCode, timeZone, customerId))
		}
	} else {
		tableRows.WriteString(`
			<tr>
				<td colspan="9" style="text-align: center; color: #666;">
					No customers found. Create a customer to get started!
				</td>
			</tr>
		`)
	}

	table := fmt.Sprintf(`
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
				%s
			</tbody>
		</table>
	`, tableRows.String())

	w.Header().Set("Content-Type", "text/html")
	w.WriteHeader(http.StatusOK)
	w.Write([]byte(table))
}

func deleteCustomerAPI(w http.ResponseWriter, r *http.Request) {
	vars := mux.Vars(r)
	customerIdStr := vars["customerId"]

	// Convert customer ID to int
	customerId, err := strconv.Atoi(customerIdStr)
	if err != nil {
		log.Printf("Invalid customer ID format: %s", customerIdStr)
		w.Header().Set("Content-Type", "text/html")
		w.WriteHeader(http.StatusBadRequest)
		w.Write([]byte(`<td colspan="9">Invalid customer ID</td>`))
		return
	}

	ctx := context.Background()

	// Call the delete customer API
	result, err := payabliClient.Customer.DeleteCustomer(ctx, customerId)
	if err != nil {
		log.Printf("Error deleting customer with ID %v: %v", customerId, err)
		w.Header().Set("Content-Type", "text/html")
		w.WriteHeader(http.StatusInternalServerError)
		w.Write([]byte(fmt.Sprintf(`<td colspan="9">Error deleting customer: %v</td>`, err)))
		return
	}

	log.Printf("Customer deleted: %+v", result)
	w.Header().Set("Content-Type", "text/html")
	w.WriteHeader(http.StatusOK)
	w.Write([]byte(""))
}

// Helper functions for safe value extraction
func safeStringValue(ptr *string) string {
	if ptr == nil {
		return ""
	}
	return *ptr
}

func safeIntValue(ptr *int) int {
	if ptr == nil {
		return 0
	}
	return *ptr
}
