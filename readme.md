
## Testing 
Important:
Run ```php artisan passport:install```

######Admin User
u admin@example.com p 12345678

######Test user
u test-user@example.com p 12345678 

######Login Route
POST {{url}}api/login?email=admin@example.com&password=12345678&remember_me=true

######Sign-up Route
POST {{url}}api/register?name=Ben&email=hi@fun.com&password=12345678&password_confirmation=12345678

######Logout Route
GET {{url}}api/logout
Headers
Authorization: Bearer {{token}}

######Users Route - Index
GET {{url}}api/users/

######Users Route with images
GET {{url}}api/users/?include=images

######User Route - Show
GET {{url}}api/users/2

######User Route - Delete

DELETE {{url}}api/users/2

######User Route - Update

DELETE {{url}}api/users/2

######User Route with images
GET {{url}}api/users/2?include=images

######Filter User by email
GET {{url}}api/users/?filter[email]=test-user@example.com
