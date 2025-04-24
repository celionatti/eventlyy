<?php

declare(strict_types=1);

/**
 * ======================================
 * ===============        ===============
 * ===== Event Model
 * ===============        ===============
 * ======================================
 */

namespace PhpStrike\app\models;

use celionatti\Bolt\Model\Model;
use PhpStrike\app\models\Ticket;

class Event extends Model
{
    protected $primaryKey = "event_id";
    protected $fillable = ['event_id', 'user_id', 'tags', 'category', 'name', 'image', 'description', 'location', 'date_time', 'phone', 'mail', 'socials', 'is_highlighted', 'status', 'ticket_sale'];

    public function other_events($id)
    {
        $sql = "SELECT events.*, COALESCE(MIN(CASE WHEN tickets.price > 0 THEN tickets.price END), 0) as lowest_price FROM events LEFT JOIN tickets ON events.event_id = tickets.event_id WHERE events.event_id != :id AND events.status = :status GROUP BY events.event_id ORDER BY events.date_time ASC LIMIT 6";

        return $this->query($sql, ['id' => $id, 'status' => 'open'], "assoc")['result'];
    }

    public function highlighted_count()
    {
        return $this->query("SELECT COUNT(*) as count FROM events WHERE is_highlighted = 1;", [], "assoc")['result'][0];
    }

    public function remove_oldest_highlighted()
    {
        return $this->query("UPDATE events SET is_highlighted = 0 WHERE event_id = (SELECT event_id FROM events WHERE is_highlighted = 1 ORDER BY updated_at ASC LIMIT 1);", [], "assoc")['result'][0];
    }

    public function highlighted_events()
    {
        $sql = "SELECT events.*, COALESCE(MIN(CASE WHEN tickets.price > 0 THEN tickets.price END), 0) as lowest_price FROM events LEFT JOIN tickets ON events.event_id = tickets.event_id WHERE events.status = :status AND events.is_highlighted = :is_highlighted GROUP BY events.event_id ORDER BY events.created_at DESC LIMIT 3";

        return $this->query($sql, ['status' => 'open', 'is_highlighted' => 1], "assoc")['result'];
    }

    public function category_highlighted_events($category_id)
    {
        $sql = "SELECT events.*, COALESCE(MIN(CASE WHEN tickets.price > 0 THEN tickets.price END), 0) as lowest_price FROM events LEFT JOIN tickets ON events.event_id = tickets.event_id WHERE events.status = :status AND events.is_highlighted = :is_highlighted AND events.category = :category_id GROUP BY events.event_id ORDER BY events.created_at DESC LIMIT 2";

        return $this->query($sql, ['status' => 'open', 'is_highlighted' => 1, 'category_id' => $category_id], "assoc")['result'];
    }

    public function event_tickets($user_id)
    {
        return $this->query("SELECT * FROM events WHERE user_id = (SELECT user_id FROM users WHERE user_id = :user_id);", ['user_id' => $user_id], "assoc")['result'];
    }
}