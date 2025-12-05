<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    public function index()
    {
        // Données fictives pour les messages
        $messages = [
            [
                'id' => 1,
                'sender' => 'Mamadou Diallo',
                'sender_email' => 'mamadou@example.com',
                'subject' => 'Demande d\'aide urgente',
                'content' => 'Bonjour, j\'ai besoin d\'aide pour...',
                'status' => 'unread',
                'priority' => 'high',
                'created_at' => now()->subMinutes(30)
            ],
            [
                'id' => 2,
                'sender' => 'Fatou Sarr',
                'sender_email' => 'fatou@example.com',
                'subject' => 'Question sur les stocks',
                'content' => 'Pouvez-vous me renseigner sur...',
                'status' => 'read',
                'priority' => 'medium',
                'created_at' => now()->subHours(2)
            ],
            [
                'id' => 3,
                'sender' => 'Ibrahima Ba',
                'sender_email' => 'ibrahima@example.com',
                'subject' => 'Remerciements',
                'content' => 'Merci pour votre aide...',
                'status' => 'replied',
                'priority' => 'low',
                'created_at' => now()->subDays(1)
            ]
        ];

        $stats = [
            'total' => 156,
            'unread' => 23,
            'read' => 98,
            'replied' => 35
        ];

        return view('admin.messages.index', compact('messages', 'stats'));
    }

    public function show($id)
    {
        $message = [
            'id' => $id,
            'sender' => 'Mamadou Diallo',
            'sender_email' => 'mamadou@example.com',
            'sender_phone' => '+221 77 123 45 67',
            'subject' => 'Demande d\'aide urgente',
            'content' => 'Bonjour, j\'ai besoin d\'aide pour une situation urgente dans notre région. Pouvez-vous nous aider ?',
            'status' => 'unread',
            'priority' => 'high',
            'created_at' => now()->subMinutes(30),
            'attachments' => []
        ];

        return view('admin.messages.show', compact('message'));
    }

    public function reply(Request $request, $id)
    {
        // Logique de réponse
        return redirect()->route('admin.messages.show', $id)->with('success', 'Réponse envoyée avec succès');
    }

    public function markAsRead($id)
    {
        // Logique de marquage comme lu
        return response()->json(['success' => true]);
    }
}



