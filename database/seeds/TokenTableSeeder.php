<?php

use Illuminate\Database\Seeder;
use App\Models\Token;

class TokenTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Inserta valores iniciales admin
        Token::create([
            'comercio_uuid' => '176f76a8-2670-4288-9800-1dd5f031a57e',
            'id' => '5804291bc7bfef35cd85548c9891502ec9f900844f2aba59fd074752f0585bc3779dd19124f7b826',
            'token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjU4MDQyOTFiYzdiZmVmMzVjZDg1NTQ4Yzk4OTE1MDJlYzlmOTAwODQ0ZjJhYmE1OWZkMDc0NzUyZjA1ODViYzM3NzlkZDE5MTI0ZjdiODI2In0.eyJhdWQiOiIxIiwianRpIjoiNTgwNDI5MWJjN2JmZWYzNWNkODU1NDhjOTg5MTUwMmVjOWY5MDA4NDRmMmFiYTU5ZmQwNzQ3NTJmMDU4NWJjMzc3OWRkMTkxMjRmN2I4MjYiLCJpYXQiOjE1MTU4MDk0MDksIm5iZiI6MTUxNTgwOTQwOSwiZXhwIjoxNTQ3MzQ1NDA5LCJzdWIiOiIxIiwic2NvcGVzIjpbInN1cGVyYWRtaW4iXX0.FnDV4lZcnJcmZ4-t9JbqOcLewuzWlfw9FMEgiXIaOS1BjG8xJubU9woBsWPxz-cv0egmpXUsHBnM394RPWyEpVni5R3AeOdSuuYVb0IcLojk9-Gz40M9UcrVSkBhCIHtUxxJo6pE4K4zF1FNSQpqcvw3rM9Ok1s0nCiVHtok4H3V7gA58vE9ihYYRpKks0CCMYjoQ9H_RlT46sujCK8zq-aSlj4bfbCYMFdZo0ptGU3kXWF3xYOe9l1-Ls3odxq40VJAj0Y97wk40-Ff2bTFmTO99Os3SAJyALyIFVAIKpQVUA3yumh6EGdZncs3lUO5kURnEuRtjaTtqcYwUkGvgGv9hP4xAfskAUmc_LMPjwmR93tmmYhCT9v6E-Tz8ZGdHzNW6Vu_fqRrSsFF7kUDPdKKbDHGHy6QdtFj5oma1Q2sKTbDd_sYyFquQ8ZxuR8NdoJRuiHT1DhohA-l2-exBRfMATScGU3ZXuyqcLRYk69fDwW5UtCSrMcQIkBwEo6qWnahPMO-_ojxvNZrNfM7PPvQ1fCIE2d8V9uMIA1jNFCKpVpekoXStxcC_hrD3MeyIMdU06lH_80XTv-n7Sj4NZw2uUtSRm4v2YKfScsfZ5fGkNmGJHdDZC00qFd4j4c38U_aGo4xX0kK1jjOO6xqu-WYpXJ_UTMo904AX43W6Kc',
        ]);
    }
}
