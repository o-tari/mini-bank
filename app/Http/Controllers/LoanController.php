<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Services\LoanService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    protected $loanService;
    protected $notificationService;

    public function __construct(LoanService $loanService, NotificationService $notificationService)
    {
        $this->loanService = $loanService;
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        $loans = Loan::with(['user', 'approver'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('loans.index', compact('loans'));
    }

    public function show(Loan $loan)
    {
        $loan->load(['user', 'approver', 'transactions']);
        return view('loans.show', compact('loan'));
    }

    public function approve(Request $request, Loan $loan)
    {
        $request->validate([
            'approval_comments' => 'nullable|string|max:1000',
        ]);

        try {
            $this->loanService->approveLoan($loan, Auth::user(), $request->approval_comments);
            $this->notificationService->notifyLoanStatusChange($loan, 'approved');

            return redirect()->back()->with('success', 'Loan approved successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to approve loan: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, Loan $loan)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        try {
            $this->loanService->rejectLoan($loan, Auth::user(), $request->rejection_reason);
            $this->notificationService->notifyLoanStatusChange($loan, 'rejected');

            return redirect()->back()->with('success', 'Loan rejected successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to reject loan: ' . $e->getMessage());
        }
    }

    public function disburse(Loan $loan)
    {
        try {
            $this->loanService->disburseLoan($loan, Auth::user());
            $this->notificationService->notifyLoanStatusChange($loan, 'disbursed');

            return redirect()->back()->with('success', 'Loan disbursed successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to disburse loan: ' . $e->getMessage());
        }
    }

    public function updateDisbursementDate(Request $request, Loan $loan)
    {
        $request->validate([
            'disbursed_at' => 'required|date|after:now',
        ]);

        try {
            $loan->update([
                'disbursed_at' => $request->disbursed_at,
            ]);

            return redirect()->back()->with('success', 'Disbursement date updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update disbursement date: ' . $e->getMessage());
        }
    }
}
