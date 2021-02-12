<?php


namespace App\Service;

class DBTest {

    private array $movies = [
        ["id"=>"2", "title" => "Ava",
            "tagline" => "Kill. Or be killed",
            "release_date" => "25/09/2020",
            "poster"=> ""],
        ["id" => "3",
            "title" => "Bill &Ted Face the Music",
            "tagline" => "The future awaits",
            "release_date" => "24/09/2020",
            "poster"=> ""],
        ["id" => "4", "title" => "Hard Kill",
            "tagline" => "Take on a madman. Save the world.",
            "release_date" => "14/09/2020",
            "poster"=> ""],
        ["id" => "5", "title" => "The Owners", "tagline" => "",
            "release_date" => "10/05/2020",
            "poster"=> ""],
        ["id" => "6", "title" => "The New Mutants",
            "tagline" => "It's time to face your demons.",
            "release_date" => "20/04/2020",
            "poster"=> ""],
    ];

    public function get():array {
        return $this->movies;
    }
}