#!/usr/bin/env sh

AUTH_DOMAIN=auth.wimc.online
AUTH_REALM_NAME=wimc.localhost
AUTH_CLIENT_ID=api.wimc.localhost
AUTH_CLIENT_SECRET=073c6e58-7fd2-407d-be45-033447e25386
API_TOKEN=$(curl -sS -X POST "https://$AUTH_DOMAIN/auth/realms/$AUTH_REALM_NAME/protocol/openid-connect/token" \
 -H "Content-Type: application/x-www-form-urlencoded" \
 -d "grant_type=client_credentials" \
 -d "client_id=$AUTH_CLIENT_ID" \
 -d "client_secret=$AUTH_CLIENT_SECRET" \
 | jq -r ".access_token")
printf 'Bearer %s' "$API_TOKEN"
