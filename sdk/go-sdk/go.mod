module payabli-go-example

go 1.21

require (
	github.com/gorilla/mux v1.8.0
	github.com/joho/godotenv v1.4.0
	sdk v0.0.0
)

require github.com/google/uuid v1.4.0 // indirect

replace sdk => ../../../fern-go-sdk
