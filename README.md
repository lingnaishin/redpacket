# redpacket
For interview purpose

## How to run
1. Composer install
2. Configure your db in .env
3. php artisan migrate
4. php artisan db:seed
5. php artisan serve

## How to consume
1. Call api http://127.0.0.1:8000/api/redpacket/create to create red packet
No authorization & authentication, so have to put user email as login
{
    "login_as": "user1@aaa.com",
    "amount": 100,
    "quantity": 3,
    "random": true
}

2. Call api http://127.0.0.1:8000/api/redpacket/receive to receive red packet
{
    "login_as": "user2@aaa.com",
    "selected_red_packet_id": 1
}

