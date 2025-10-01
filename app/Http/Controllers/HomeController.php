<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_loans' => Loan::count(),
            'total_transactions' => Transaction::count(),
            'total_loan_amount' => Loan::where('status', 'approved')->sum('amount'),
        ];

        return view('home', compact('stats'));
    }

    public function about()
    {
        return view('about');
    }

    public function faq()
    {
        $faqs = [
            [
                'question' => 'How do I apply for a loan?',
                'answer' => 'You can apply for a loan by logging into your account and filling out our simple online application form. Our system will process your application and provide an instant decision in most cases.'
            ],
            [
                'question' => 'What are the interest rates?',
                'answer' => 'Our interest rates vary based on your credit score and loan amount. Personal loans start at 4.5% APR, while business loans start at 6.0% APR. Contact us for a personalized quote.'
            ],
            [
                'question' => 'How long does loan approval take?',
                'answer' => 'Most loan applications are processed within 24 hours. For larger amounts or complex applications, it may take up to 3-5 business days.'
            ],
            [
                'question' => 'Can I make early payments?',
                'answer' => 'Yes! You can make early payments at any time without penalty. Early payments can help you save on interest and pay off your loan faster.'
            ],
            [
                'question' => 'Is my money safe?',
                'answer' => 'Absolutely. We are FDIC insured and use bank-level security to protect your personal and financial information. Your deposits are insured up to $250,000.'
            ],
            [
                'question' => 'What documents do I need?',
                'answer' => 'For most loans, you\'ll need a valid ID, proof of income (pay stubs or tax returns), and bank statements. Additional documents may be required for larger amounts.'
            ]
        ];

        return view('faq', compact('faqs'));
    }

    public function contact()
    {
        return view('contact');
    }

    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);

        // Here you would typically send an email or store the message
        // For now, we'll just redirect back with a success message

        return redirect()->route('contact')->with('success', 'Thank you for your message! We\'ll get back to you soon.');
    }
}
