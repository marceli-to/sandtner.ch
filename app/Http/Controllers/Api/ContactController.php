<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Statamic\Facades\Entry;
use Illuminate\Support\Facades\Notification;
// use App\Notifications\UserEventRegistration;
// use App\Notifications\OwnerEventRegistration;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
  public function submit(Request $request)
  {
    $validationResult = $this->validateRequest($request);

    if ($validationResult !== TRUE)
    {
      return $validationResult;
    }

    $slug = $request->input('firstname') . ' ' . $request->input('name');

    // build data
    $data = [
      'name' => $request->input('name'),
      'firstname' => $request->input('firstname'),
      'email' => $request->input('email'),
    ];

    $entry = Entry::make()
      ->collection('contact_form_submissions')
      ->slug($slug)
      ->data($data)
      ->save();
    
    // Notification::route('mail', $request->input('email'))
    //   ->notify(new UserEventRegistration($data)
    // );

    // Notification::route('mail', env('MAIL_TO'))
    //   ->notify(new OwnerEventRegistration($data)
    // );

    return response()->json(['message' => 'Store successful']);
  }

  protected function validateRequest(Request $request)
  {
    $validationRules = $this->getValidationRules();

    $validator = Validator::make(
      $request->all(),
      $validationRules['rules'],
      $validationRules['messages']
    );

    if ($validator->fails())
    {
      $errors = $validator->errors();
      $formattedErrors = [];

      foreach ($errors->messages() as $field => $messages)
      {
        $formattedErrors[$field] = $messages[0];
      }

      return response()->json(['errors' => $formattedErrors], 422);
    }

    return TRUE;
  }

  protected function getValidationRules()
  {
    $validationRules = [
      'name' => 'required',
      'firstname' => 'required',
      'email' => 'required|email|regex:/^[^\s@]+@[^\s@]+\.[^\s@]+$/',
    ];

    // Set validation messages
    $validationMessages = [
      'name.required' => 'Name ist erforderlich',
      'firstname.required' => 'Vorname ist erforderlich',
      'email.required' => 'E-Mail-Adresse ist erforderlich',
      'email.email' => 'E-Mail-Adresse muss gültig sein',
      'email.regex' => 'E-Mail-Adresse muss gültig sein',
    ];
    
    return [
      'rules' => $validationRules,
      'messages' => $validationMessages,
    ];
  }
}