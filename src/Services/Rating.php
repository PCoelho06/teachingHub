<?php

namespace App\Services;

use App\Entity\Document;

class Rating
{
    static public function calculateRating(Document $document,  $rating = 0): ?float
    {
        $comments = $document->getComments();
        $total = $rating;

        foreach ($comments as $comment) {
            $total += $comment->getRating();
        }

        return ($comments->count() === 0) ? $total : ($total / $comments->count());
    }
}
