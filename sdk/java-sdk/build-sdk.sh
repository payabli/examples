#!/bin/bash

echo "🔧 Building Payabli Java SDK..."

# Navigate to the Java SDK directory and build it
cd ../../../fern-java-sdk

# Only rebuild if the JAR doesn't exist
if [ ! -f "build/libs/api-sdk-0.0.209.jar" ]; then
    echo "🔧 Running Gradle build..."
    ./gradlew clean build -q
    
    if [ $? -ne 0 ]; then
        echo "❌ SDK build failed"
        exit 1
    fi
fi

echo "✅ SDK is ready"

# Copy the built JAR to our project's lib directory
cd ../examples/sdk/java-sdk
mkdir -p lib

# Look for the main JAR file
SDK_JAR=""
for jar in ../../../fern-java-sdk/build/libs/api-sdk-*.jar; do
    if [[ ! "$(basename "$jar")" =~ (sources|javadoc) ]] && [ -f "$jar" ]; then
        SDK_JAR="$jar"
        break
    fi
done

if [ -n "$SDK_JAR" ] && [ -f "$SDK_JAR" ]; then
    cp "$SDK_JAR" lib/api-sdk.jar
    echo "✅ SDK JAR copied to lib/api-sdk.jar"
    echo "📦 Size: $(ls -lh lib/api-sdk.jar | awk '{print $5}')"
else
    echo "❌ Could not find SDK JAR file"
    echo "Available JAR files:"
    ls -la ../../../fern-java-sdk/build/libs/ || echo "Build directory not found"
    exit 1
fi
