<?php

/**
 * Check whether the url/path is match to the current route
 */
if (! function_exists('route_named')) {
    function route_named(string $path): bool
    {
        return request()->route()->named($path);
    }
}

if(! function_exists('quote')) {
    function quote() {
        $inspirationalQuotes = [
            "Believe you can and you're halfway there. -Theodore Roosevelt",
            "The only way to do great work is to love what you do. -Steve Jobs",
            "Your time is limited, don't waste it living someone else's life. -Steve Jobs",
            "The future belongs to those who believe in the beauty of their dreams. -Eleanor Roosevelt",
            "Don't watch the clock; do what it does. Keep going. -Sam Levenson",
            "You are never too old to set another goal or to dream a new dream. -C.S. Lewis",
            "Strive not to be a success, but rather to be of value. -Albert Einstein",
            "The only limit to our realization of tomorrow will be our doubts of today. -Franklin D. Roosevelt",
            "If you want to achieve greatness stop asking for permission. -Anonymous",
            "The only place where success comes before work is in the dictionary. -Vidal Sassoon",
            "The road to success and the road to failure are almost exactly the same. -Colin R. Davis",
            "Don't be afraid to give up the good to go for the great. -John D. Rockefeller",
            "I find that the harder I work, the more luck I seem to have. -Thomas Jefferson",
            "Success is not final, failure is not fatal: It is the courage to continue that counts. -Winston Churchill",
            "Opportunities don't happen. You create them. -Chris Grosser",
            "Success is stumbling from failure to failure with no loss of enthusiasm. -Winston S. Churchill",
            "The only place where success comes before work is in the dictionary. -Vidal Sassoon",
            "The only way to achieve the impossible is to believe it is possible. -Charles Kingsleigh",
            "Success is not how high you have climbed, but how you make a positive difference to the world. -Roy T. Bennett",
            "Your work is going to fill a large part of your life, and the only way to be truly satisfied is to do what you believe is great work. And the only way to do great work is to love what you do. -Steve Jobs",
            "Don`t be pushed around by the fears in your mind. Be led by the dreams in your heart. -Roy T. Bennett",
            "It always seems impossible until it's done. -Nelson Mandela",
            "The only limit to our realization of tomorrow will be our doubts of today. -Franklin D. Roosevelt",
            "Your attitude, not your aptitude, will determine your altitude. -Zig Ziglar",
            "Don`t be afraid to give up the good to go for the great. -John D. Rockefeller",
            "The only thing standing between you and your goal is the story you keep telling yourself as to why you can't achieve it. -Jordan Belfort",
            "I have not failed. I've just found 10,000 ways that won't work. -Thomas A. Edison",
            "The only place where success comes before work is in the dictionary. -Vidal Sassoon",
            "Don`t watch the clock; do what it does. Keep going. -Sam Levenson",
            "You don't have to be great to start, but you have to start to be great. -Zig Ziglar",
            "Success is not final, failure is not fatal: It is the courage to continue that counts. -Winston Churchill",
            "Success is not in what you have, but who you are. -Bo Bennett",
            "The only way to do great work is to love what you do. -Steve Jobs",
            "The only thing standing between you and your goal is the story you keep telling yourself as to why you can't achieve it. -Jordan Belfort",
            "Believe in yourself and all that you are. Know that there is something inside you that is greater than any obstacle. -Christian D. Larson",
            "The only way to achieve the impossible is to believe it is possible. -Charles Kingsleigh",
            "I find that the harder I work, the more luck I seem to have. -Thomas Jefferson",
            "Success usually comes to those who are too busy to be looking for it. -Henry David Thoreau",
            "Don't be afraid to give up the good to go for the great. -John D. Rockefeller",
            "The future belongs to those who believe in the beauty of their dreams. -Eleanor Roosevelt",
            "It's not whether you get knocked down, it's whether you get up. -Vince Lombardi",
            "Success is not in what you have, but who you are. -Bo Bennett",
            "Opportunities don't happen. You create them. -Chris Grosser",
            "The only limit to our realization of tomorrow will be our doubts of today. -Franklin D. Roosevelt",
            "Your attitude, not your aptitude, will determine your altitude. -Zig Ziglar",
            "The only way to achieve the impossible is to believe it is possible. -Charles Kingsleigh",
            "The only limit to our realization of tomorrow will be our doubts of today. -Franklin D. Roosevelt",
        ];

        return collect($inspirationalQuotes)->random();
    }
}
