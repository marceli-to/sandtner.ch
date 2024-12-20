<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Statamic\Facades\Entry;
use Illuminate\Support\Facades\Notification;
use App\Notifications\DamageReport\OwnerInformation;
use App\Notifications\DamageReport\UserConfirmation;
use Illuminate\Support\Facades\Validator;

class DamageReportController extends Controller
{
  public function submission(Request $request): \Illuminate\Http\JsonResponse
  {
    $validationResult = $this->validateRequest($request);

    if ($validationResult !== TRUE)
    {
      return $validationResult;
    }

    // Set slug
    $slug = Str::slug($request->input('email'));

    // build data
    $data = [
      'title' => $request->input('firstname') . ' ' . $request->input('name') . ', ' . $request->input('email'),
      'firstname' => $request->input('firstname'),
      'name' => $request->input('name'),
      'email' => $request->input('email'),
      'phone' => $request->input('phone'),
      'message' => $request->input('message'),
      'damage_description' => $request->input('damage_description'),
      'control_plate' => $request->input('control_plate'),
      'vehicle' => $request->input('vehicle'),
      'images_registration_card' => $this->handleUploads($request->file('images_registration_card'), $request, $slug),
      'images_mileage' => $this->handleUploads($request->file('images_mileage'), $request, $slug),
      'images_vehicle' => $this->handleUploads($request->file('images_vehicle'), $request, $slug),
      'images_damage' => $this->handleUploads($request->file('images_damage'), $request, $slug),
      'insurance_company' => $request->input('insurance_company'),
      'insurance_type' => $request->input('insurance_type'),
    ];

    $entry = Entry::make()
      ->collection('damage_reports')
      ->slug($slug)
      ->data($data)
      ->save();

    // We need to clear the cache, otherwise the manually added assets
    // will not show up in the backend
    \Artisan::call('cache:clear');

    Notification::route('mail', env('MAIL_TO'))
      ->notify(new OwnerInformation($data)
    );

    Notification::route('mail', $request->input('email'))
      ->notify(new UserConfirmation($data)
    );

    return response()->json(['message' => 'Store successful']);
  }

  private function validateRequest(Request $request): bool
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

  private function getValidationRules(): array
  {
    // Set validation rules
    $validationRules = [
      'firstname' => 'required',
      'name' => 'required',
      'email' => 'required|email|regex:/^[^\s@]+@[^\s@]+\.[^\s@]+$/',
      'phone' => 'required',
      'message' => 'required',
      'control_plate' => 'required',
      'damage_description' => 'required',
      'vehicle' => 'required',
      'insurance_company' => 'required',
      'insurance_type' => 'required',
      'images_registration_card' => 'required',
      'images_mileage' => 'required',
      'images_vehicle' => 'required',
      'images_damage' => 'required',
    ];

    // Set validation messages
    $validationMessages = [
      'firstname.required' => 'Vorname ist erforderlich',
      'name.required' => 'Name ist erforderlich',
      'email.required' => 'E-Mail-Adresse ist erforderlich',
      'email.email' => 'E-Mail-Adresse muss gültig sein',
      'email.regex' => 'E-Mail-Adresse muss gültig sein',
      'phone.required' => 'Telefon ist erforderlich',
      'message.required' => 'Nachricht ist erforderlich',
      'control_plate.required' => 'Kennzeichen ist erforderlich',
      'damage_description.required' => 'Beschreibung ist erforderlich',
      'vehicle.required' => 'Fahrzeugmarke, Fahrzeugtyp ist erforderlich',
      'insurance_company.required' => 'Versicherungsgesellschaft ist erforderlich',
      'insurance_type.required' => 'Deckungsart ist erforderlich',
      'images_registration_card.required' => 'Bilder Fahrzeugausweis sind erforderlich',
      'images_mileage.required' => 'Bilder Kilometerstand sind erforderlich',
      'images_vehicle.required' => 'Bilder Fahrzeug sind erforderlich',
      'images_damage.required' => 'Bilder Schaden sind erforderlich',
    ];
    
    return [
      'rules' => $validationRules,
      'messages' => $validationMessages,
    ];
  }

  private function handleUploads($images = [], $request, $slug): array
  {
    $uploaded_images = [];
    foreach($images as $image)
    {
      if ($image)
      {
        $request->validate([
          'image' => 'image|mimes:jpeg,jpg,png|max:16384',
        ]);

        $image_name = $this->cleanUpFilename($image->getClientOriginalName()) . '-' . Str::random(16) . '.' . $image->getClientOriginalExtension();
        $uploaded_images[] = $image->storeAs('uploads_schadenmeldung', $image_name, 'assets');
      }
    }

    return $uploaded_images;
  }

  private function cleanUpFilename($filename): string
  {
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    $name = pathinfo($filename, PATHINFO_FILENAME);
    
    // Convert to lowercase
    $name = strtolower($name);
    
    // Transliterate non-ASCII characters to their ASCII equivalents
    $name = transliterator_transliterate('Any-Latin; Latin-ASCII', $name);
    
    // Remove any remaining non-alphanumeric characters except dashes and underscores
    $name = preg_replace('/[^a-z0-9-_]/', '', $name);
    
    // Replace multiple dashes or underscores with a single underscore
    $name = preg_replace('/-+/', '_', $name);
    $name = preg_replace('/_+/', '_', $name);
    
    // Trim underscores from beginning and end
    $name = trim($name, '_');

    return $name;
  }
}