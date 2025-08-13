#!/bin/bash

echo "🔧 Building and installing Payabli Java SDK..."

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

echo "✅ SDK build ready"

# Find the main JAR file
SDK_JAR=""
for jar in build/libs/api-sdk-*.jar; do
    if [[ ! "$(basename "$jar")" =~ (sources|javadoc) ]] && [ -f "$jar" ]; then
        SDK_JAR="$jar"
        break
    fi
done

if [ -n "$SDK_JAR" ] && [ -f "$SDK_JAR" ]; then
    echo "📦 Found SDK JAR: $(basename "$SDK_JAR")"
    
    # Install the JAR to local Maven repository
    mvn install:install-file \
        -Dfile="$SDK_JAR" \
        -DgroupId=com.payabli \
        -DartifactId=api-sdk \
        -Dversion=1.0.0 \
        -Dpackaging=jar \
        -q
    
    if [ $? -eq 0 ]; then
        echo "✅ SDK installed to Maven local repository"
        
        # Also copy to lib directory as backup
        cd ../examples/sdk/java-sdk
        mkdir -p lib
        cp "../../../fern-java-sdk/$SDK_JAR" lib/api-sdk.jar
        echo "✅ SDK JAR also copied to lib/api-sdk.jar"
    else
        echo "❌ Failed to install SDK to Maven repository"
        exit 1
    fi
else
    echo "❌ Could not find SDK JAR file"
    echo "Available JAR files:"
    ls -la build/libs/ || echo "Build directory not found"
    exit 1
fi
