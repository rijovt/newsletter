<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Subscriber;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * functon to show the application dashboard.
     * Param: Nil
     * Retun: list of subscribers
     */
    public function index()
    {
        $subscribers = Subscriber::latest()->paginate(25);

        return view('home', compact('subscribers'));
    }

    /*
     * functon to add subscribers
     * Param : Request data of ajax add
     * Retun : data validation messages/success     * 
    */
    public function additem(Request $request)
    { 
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:subscribers'
        ]);

        $request['user_id'] = Auth::user()->id;
        $request['name'] = ucfirst($request['name']); 
        $subscriber = Subscriber::create($request->all());

        return response()->json($subscriber, 200);
    }

    /*
     * functon to remove each subscriber
     * Param : id of perticular subscriber
     * Retun : success
    */
    public function destroyitem(Subscriber $item)
    {
        $item->delete();
        return response()->json('success', 200);
    }

    /*
     * functons to redirect subscriber list selected to compose mail
     * Param : checked subscribers id
     * Retun : json data of checked subscribers ids
    */
    public function compose(Request $request)
    {  
        $selectedSubscribers = json_encode($request['checks']);

        return view('composeMail', compact('selectedSubscribers'));
    }

    /*
     * functons to send mail
     * Param : checked subscribers ids,mail data
     * Retun : redirect to home with success msg
    */
    public function sendMail(Request $request)
    {
        $mailDetails = Subscriber::whereIn('id', json_decode($request['selectedSubscribers']))->get();

        $details = [
            'sub' => $request['subject'],
            'msg'=> $request['message'],
            'mails'=> $mailDetails
        ];
        
        $job = (new \App\Jobs\SendQueueEmail($details))
                ->delay(now()->addSeconds(2)); 

        dispatch($job);
        return redirect()
            ->route('home')
            ->with('message','Mail send successfully !!');
    }
}
