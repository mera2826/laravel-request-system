<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ServiceRequestController extends Controller
{
    /**
     * Display the requests available to the authenticated user.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', ServiceRequest::class);

        $user = $request->user();

        $query = ServiceRequest::query();

        if (! $user->isAdministrator()) {
            $query->where('user_id', $user->id);
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();

            $query->where(function ($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%")
                    ->orWhere('purpose', 'like', "%{$search}%");
            });
        }

        $requests = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('requests.index', compact('requests'));
    }

    /**
     * Display a specific service request.
     */
    public function show(ServiceRequest $serviceRequest)
    {
        Gate::authorize('view', $serviceRequest);

        return view('requests.show', compact('serviceRequest'));
    }

    /**
     * Display the request creation form.
     */
    public function create()
    {
        Gate::authorize('create', ServiceRequest::class);

        return view('requests.create');
    }

    /**
     * Store a new service request.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', ServiceRequest::class);

        $validated = $request->validate([
            'item_name' => ['required', 'string', 'max:150'],
            'quantity' => ['required', 'integer', 'min:1'],
            'purpose' => ['required', 'string', 'max:2000'],
        ]);

        $user = $request->user();

        ServiceRequest::create([
            'user_id' => $user->id,
            'requester_name' => $user->name,
            'requester_email' => $user->email,
            'item_name' => $validated['item_name'],
            'quantity' => $validated['quantity'],
            'purpose' => $validated['purpose'],
            'status' => 'pending',
        ]);

        return redirect()
            ->route('requests.index')
            ->with('success', 'Request submitted successfully.');
    }

    /**
     * Update the status of a service request.
     */
    public function updateStatus(Request $request, ServiceRequest $serviceRequest)
    {
        Gate::authorize('updateStatus', $serviceRequest);

        $validated = $request->validate([
            'status' => ['required', 'in:pending,approved,rejected'],
        ]);

        $serviceRequest->update([
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('requests.show', $serviceRequest)
            ->with('success', 'Request status updated successfully.');
    }
}