<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Statamic\Facades\Entry;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Appointment\OwnerInformation;
use App\Notifications\Appointment\UserConfirmation;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
  public function request(Request $request)
  {
    $validationResult = $this->validateRequest($request);

    if ($validationResult !== TRUE)
    {
      return $validationResult;
    }

    $slug = $request->input('firstname') . '-' . $request->input('name') . '-' . $request->input('email');

    // build data
    $data = [
      'title' => $request->input('firstname') . ' ' . $request->input('name') . ', ' . $request->input('email'),
      // date has format 2024-12-31T23:00:00.000Z
      // transform to 31.12.2024
      'appointment_date' => date('d.m.Y', strtotime($request->input('date'))),
      'appointment_time' => $request->input('time'),
      'appointment_reason' => $request->input('reason'),
      'appointment_control_plate' => $request->input('control_plate'),
      'appointment_message' => $request->input('message'),
      'appointment_name' => $request->input('name'),
      'appointment_firstname' => $request->input('firstname'),
      'appointment_email' => $request->input('email'),
      'appointment_phone' => $request->input('phone'),
    ];

    $entry = Entry::make()
      ->collection('appointment_requests')
      ->slug($slug)
      ->data($data)
      ->save();

    Notification::route('mail', env('MAIL_TO'))
      ->notify(new OwnerInformation($data)
    );

    Notification::route('mail', $request->input('email'))
      ->notify(new UserConfirmation($data)
    );

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
    // Set validation rules
    $validationRules = [
      'date' => 'required',
      'time' => 'required',
      'reason' => 'required',
      'control_plate' => 'required',
      'message' => 'required',
      'name' => 'required',
      'firstname' => 'required',
      'email' => 'required|email|regex:/^[^\s@]+@[^\s@]+\.[^\s@]+$/',
      'phone' => 'required',
    ];

    // Set validation messages
    $validationMessages = [
      'date.required' => 'Datum ist erforderlich',
      'time.required' => 'Zeit ist erforderlich',
      'reason.required' => 'Grund ist erforderlich',
      'control_plate.required' => 'Kennzeichen ist erforderlich',
      'message.required' => 'Nachricht ist erforderlich',
      'name.required' => 'Name ist erforderlich',
      'firstname.required' => 'Vorname ist erforderlich',
      'email.required' => 'E-Mail-Adresse ist erforderlich',
      'email.email' => 'E-Mail-Adresse muss gültig sein',
      'email.regex' => 'E-Mail-Adresse muss gültig sein',
      'phone.required' => 'Telefon ist erforderlich',
    ];
    
    return [
      'rules' => $validationRules,
      'messages' => $validationMessages,
    ];
  }
}