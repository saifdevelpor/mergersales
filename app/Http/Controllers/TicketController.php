<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketAttachment;
use App\Models\TicketMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::where('user_id', auth()->id())
            ->withCount('attachments')
            ->latest()
            ->get();

        return view('tickets.index', compact('tickets'));
    }

    public function store(Request $req)
    {
        $req->validate([
            'subject'   => 'required|string|max:255',
            'category'  => 'required|string|max:100',
            'message'   => 'required|string',

            // attachments validation
            'attachments'   => 'nullable|array|max:5',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,webp|max:5120', // 5MB each
        ]);

        // 1️⃣ Ticket Create
        $ticket = Ticket::create([
            'ticket_no' => 'MS-' . date('Y') . '-' . rand(10000, 99999),
            'user_id'   => auth()->id(),
            'subject'   => $req->subject,
            'category'  => $req->category,
            'message'   => $req->message,
            'priority'  => $req->priority ?? 'medium',
            'status'    => 'open'
        ]);

        // 2️⃣ Attachments Upload
        if ($req->hasFile('attachments')) {

            foreach ($req->file('attachments') as $file) {

                // store file in storage/app/public/tickets/{ticket_id}
                $path = $file->store('tickets/' . $ticket->id, 'public');

                TicketAttachment::create([
                    'ticket_id'      => $ticket->id,
                    'file_path'      => $path,
                    'original_name'  => $file->getClientOriginalName(),
                    'mime'           => $file->getClientMimeType(),
                    'size'           => $file->getSize(),
                ]);
            }
        }

        return back()->with('success', 'Ticket Created with attachments!');
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['user', 'attachments']);

        if (auth()->user()->role !== 'Admin' && $ticket->user_id !== auth()->id()) {
            abort(403);
        }

        return view('tickets.show', compact('ticket'));
    }

    // ADMIN
    public function adminIndex()
    {
        if (auth()->user()->role != 'Admin') abort(403);

        $tickets = Ticket::with('user')
            ->withCount('attachments')
            ->latest()
            ->get();
        return view('tickets.admin', compact('tickets'));
    }

    public function updateStatus(Request $req, $id)
    {
        if (auth()->user()->role != 'Admin') abort(403);

        $ticket = Ticket::findOrFail($id);
        $ticket->status = $req->status;
        $ticket->save();

        return back()->with('success', 'Status Updated');
    }


    // Views Tickets According Status

    private function getTicketsByStatus($status)
    {
        $query = Ticket::with('user')
            ->withCount('attachments')
            ->where('status', $status)
            ->latest();

        // 👤 USER → only own tickets
        if (auth()->user()->role !== 'Admin') {
            $query->where('user_id', auth()->id());
        }

        return $query->get();
    }

    public function openTickets()
    {
        $tickets = $this->getTicketsByStatus('open');
        return view('tickets.open', compact('tickets'));
    }

    public function underReviewTickets()
    {
        $tickets = $this->getTicketsByStatus('under_review');
        return view('tickets.under-review', compact('tickets'));
    }

    public function completedTickets()
    {
        $tickets = $this->getTicketsByStatus('completed');
        return view('tickets.complate', compact('tickets'));
    }

    public function closedTickets()
    {
        $tickets = $this->getTicketsByStatus('closed');
        return view('tickets.close', compact('tickets'));
    }

    public function rejectedTickets()
    {
        $tickets = $this->getTicketsByStatus('rejected');
        return view('tickets.rejected', compact('tickets'));
    }
}
