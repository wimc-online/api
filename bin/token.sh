#!/usr/bin/env sh

AUTH_DOMAIN=auth.wimc.online
AUTH_REALM_NAME=wimc.localhost
AUTH_CLIENT_ID=api.wimc.localhost
API_USERNAME=courier
stty -echo
>&2 printf 'Password: '
read API_PASSWORD
stty echo
>&2 printf '\n'
API_TOKEN=$(curl -sS -X POST "https://$AUTH_DOMAIN/auth/realms/$AUTH_REALM_NAME/protocol/openid-connect/token" \
 -H "Content-Type: application/x-www-form-urlencoded" \
 -d "client_id=$AUTH_CLIENT_ID" \
 -d "username=$API_USERNAME" \
 -d "password=$API_PASSWORD" \
 -d "grant_type=password" \
 | jq -r ".access_token")
printf 'Bearer %s' "$API_TOKEN"
