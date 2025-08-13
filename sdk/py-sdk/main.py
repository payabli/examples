import os
from typing import Optional
from fastapi import FastAPI, Request, Form, HTTPException
from fastapi.responses import HTMLResponse
from fastapi.staticfiles import StaticFiles
from fastapi.templating import Jinja2Templates
from payabli import payabli
from payabli.core.api_error import ApiError
from dotenv import load_dotenv

# Load environment variables
load_dotenv()

app = FastAPI(title="Payabli SDK Example", description="A basic example using the Payabli Python SDK")

# Mount static files
app.mount("/static", StaticFiles(directory="static"), name="static")

# Set up templates
templates = Jinja2Templates(directory="templates")

# Initialize Payabli client
api_key = os.getenv("PAYABLI_KEY")
entry_point = os.getenv("PAYABLI_ENTRY")

if not api_key or not entry_point:
    raise ValueError("PAYABLI_KEY and PAYABLI_ENTRY must be set in environment variables")

payabli_client = payabli(api_key=api_key)

@app.get("/", response_class=HTMLResponse)
async def create_customer_page(request: Request):
    """Render the create customer form page."""
    return templates.TemplateResponse("create.html", {"request": request})

@app.get("/list", response_class=HTMLResponse)
async def list_customers_page(request: Request):
    """Render the list customers page."""
    return templates.TemplateResponse("list.html", {"request": request})

@app.post("/api/create")
async def create_customer(
    request: Request,
    firstname: str = Form(...),
    lastname: str = Form(...),
    email: str = Form(...),
    timeZone: str = Form(...),
    address: str = Form(...),
    city: str = Form(...),
    state: str = Form(...),
    zip: str = Form(...),
    country: str = Form(...),
    hvac: Optional[str] = Form(None),
    electrical: Optional[str] = Form(None),
    terms: str = Form(...)
):
    """Create a new customer via the Payabli API."""
    try:
        # Prepare additional fields
        additional_fields = {}
        if hvac:
            additional_fields["hvac"] = hvac
        if electrical:
            additional_fields["electrical"] = electrical

        # Call the Payabli API with individual parameters (not a body object)
        result = payabli_client.customer.add_customer(
            entry=entry_point,
            force_customer_creation=True,
            firstname=firstname,
            lastname=lastname,
            email=email,
            zip=zip,
            time_zone=int(timeZone),
            country=country,
            state=state,
            city=city,
            address=address,
            additional_fields=additional_fields if additional_fields else None,
            identifier_fields=["email"]  # Required field - using email as identifier
        )
        
        print(f"Customer created successfully: {result}")
        
        return HTMLResponse(
            content='<input type="text" name="valid" value="Success!" aria-invalid="false" id="form-result" readonly>',
            status_code=201
        )
        
    except ApiError as e:
        print(f"API Error: {e}")
        return HTMLResponse(
            content='<input type="text" name="invalid" value="Error!" aria-invalid="true" id="form-result" readonly>',
            status_code=200,
            headers={"HX-Reswap": "innerHTML"}
        )
    except Exception as e:
        print(f"Unexpected error: {e}")
        return HTMLResponse(
            content='<input type="text" name="invalid" value="Error!" aria-invalid="true" id="form-result" readonly>',
            status_code=200,
            headers={"HX-Reswap": "innerHTML"}
        )

@app.get("/api/list")
async def list_customers():
    """Fetch and return a list of customers as HTML table."""
    try:
        result = payabli_client.query.list_customers(entry=entry_point)
        
        # Build table rows
        table_rows = ""
        if hasattr(result, 'records') and result.records:
            for record in result.records:
                # Use the correct field names from the CustomerQueryRecords model
                firstname = getattr(record, 'firstname', '') or ''
                lastname = getattr(record, 'lastname', '') or ''
                email = getattr(record, 'email', '') or ''
                address = getattr(record, 'address', '') or ''
                city = getattr(record, 'city', '') or ''
                state = getattr(record, 'state', '') or ''
                zip_code = getattr(record, 'zip', '') or ''
                time_zone = getattr(record, 'time_zone', '') or ''
                customer_id = getattr(record, 'customer_id', '') or ''
                
                table_rows += f'''
                <tr>
                  <td>{firstname}</td>
                  <td>{lastname}</td>
                  <td>{email}</td>
                  <td>{address}</td>
                  <td>{city}</td>
                  <td>{state}</td>
                  <td>{zip_code}</td>
                  <td>{time_zone}</td>
                  <td>
                    <button id="delete" class="outline"
                      hx-delete="/api/delete/{customer_id}" 
                      hx-swap="innerHTML" 
                      hx-target="closest tr"  
                      hx-on="htmx:beforeRequest: this.setAttribute('aria-busy', 'true'), htmx:afterRequest: this.removeAttribute('aria-busy')"
                    >
                      ❌
                    </button>
                  </td>
                </tr>
                '''
        else:
            table_rows = '''
                <tr>
                  <td colspan="9" style="text-align: center; color: #666;">
                    No customers found. Create a customer to get started!
                  </td>
                </tr>
            '''
        
        table = f'''
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
            {table_rows}
          </tbody>
        </table>
        '''
        
        return HTMLResponse(content=table, status_code=200)
        
    except Exception as e:
        print(f"Error listing customers: {e}")
        return HTMLResponse(
            content='<p>Error loading customers. Please check your API credentials and try again.</p>',
            status_code=500
        )

@app.delete("/api/delete/{customer_id}")
async def delete_customer(customer_id: str):
    """Delete a customer by ID."""
    try:
        # Convert customer_id to int as required by the API
        customer_id_int = int(customer_id)
        result = payabli_client.customer.delete_customer(customer_id_int)
        print(f"Customer deleted: {result}")
        return HTMLResponse(content="", status_code=200)
    except ValueError:
        print(f"Invalid customer ID format: {customer_id}")
        return HTMLResponse(
            content=f'<td colspan="9">Invalid customer ID</td>',
            status_code=400
        )
    except Exception as e:
        print(f"Error deleting customer: {e}")
        return HTMLResponse(
            content=f'<td colspan="9">Error deleting customer: {str(e)}</td>',
            status_code=500
        )

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8000, reload=True)
