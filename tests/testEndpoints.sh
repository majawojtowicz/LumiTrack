#!/bin/bash

URL="http://web"

echo "LumiTrack Endpoint "

test_endpoint() {
    local endpoint=$1
    local expected_code=$2
    local description=$3

    actual_code=$(curl -s -o /dev/null -w "%{http_code}" "$URL$endpoint")

    if [ "$actual_code" -eq "$expected_code" ]; then
        echo "OK $description ($endpoint) - Status: $actual_code"
    else
        echo "FAIL $description ($endpoint) - Oczekiwano: $expected_code, Otrzymano: $actual_code"
    fi
}


test_endpoint "/login" 200
test_endpoint "/register" 200
test_endpoint "/jakas-dziwna-strona" 404
test_endpoint "/admin" 401
test_endpoint "/profile" 401
test_endpoint "/admin-toggle-block" 500
test_endpoint "/delete-entry" 400
test_endpoint "/update-profile" 302
test_endpoint "/logout" 302
test_endpoint "/history" 401
test_endpoint "/save-entry" 405

echo "koniec"