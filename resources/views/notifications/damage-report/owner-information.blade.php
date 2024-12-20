<x-mail::message>
  <div class="text-base">
    Online Schadenmeldung
  </div>
  <br>
  @if ($data['firstname'])
    <div class="text-base">
      <strong>Vorname</strong><br>
      {{ $data['firstname'] }}
    </div>
    <br>
  @endif
  @if ($data['name'])
    <div class="text-base">
      <strong>Name</strong><br>
      {{ $data['name'] }}
    </div>
    <br>
  @endif
  @if ($data['email'])
    <div class="text-base">
      <strong>E-Mail</strong><br>
      {{ $data['email'] }}
    </div>
    <br>
  @endif
  @if ($data['phone'])
    <div class="text-base">
      <strong>Telefon</strong><br>
      {{ $data['phone'] }}
    </div>
    <br>
  @endif
  @if ($data['message'])
    <div class="text-base">
      <strong>Nachricht</strong><br>
      {{ nl2br($data['message']) }}
    </div>
    <br>
  @endif
  @if ($data['damage_description'])
    <div class="text-base">
      <strong>Art des Schadens</strong><br>
      {{ $data['damage_description'] }}
    </div>
    <br>
  @endif
  @if ($data['vehicle'])
    <div class="text-base">
      <strong>Fahrzeugmarke/Fahrzeugtyp</strong><br>
      {{ $data['vehicle'] }}
    </div>
    <br>
  @endif
  @if ($data['control_plate'])
    <div class="text-base">
      <strong>Kontrollschild</strong><br>
      {{ $data['control_plate'] }}
    </div>
    <br>
  @endif
  @if ($data['insurance_company'])
    <div class="text-base">
      <strong>Versicherungsgesellschaft</strong><br>
      {{ $data['insurance_company'] }}
    </div>
    <br>
  @endif
  @if ($data['insurance_type'])
    <div class="text-base">
      <strong>Versicherungsdeckung</strong><br>
      {{ $data['insurance_type'] }}
    </div>
    <br>
  @endif
  @if ($data['images_registration_card'])
    <div class="text-base">
      <strong>Fotos Fahrzeugausweis</strong><br>
      @foreach($data['images_registration_card'] as $key => $image)
        <a href="{{ env('APP_URL') . '/assets/' . $image }}" target="_blank">
          {{ \Str::replace('uploads_schadenmeldung/', '', $image) }}
        </a><br>
      @endforeach
    </div>
    <br>
  @endif
  @if ($data['images_mileage'])
    <div class="text-base">
      <strong>Fotos Kilometerstand</strong><br>
      @foreach($data['images_mileage'] as $key => $image)
        <a href="{{ env('APP_URL') . '/assets/' . $image }}" target="_blank">
          {{ \Str::replace('uploads_schadenmeldung/', '', $image) }}
        </a><br>
      @endforeach
    </div>
    <br>
  @endif
  @if ($data['images_vehicle'])
    <div class="text-base">
      <strong>Fotos Fahrzeug</strong><br>
      @foreach($data['images_vehicle'] as $key => $image)
        <a href="{{ env('APP_URL') . '/assets/' . $image }}" target="_blank">
          {{ \Str::replace('uploads_schadenmeldung/', '', $image) }}
        </a><br>
      @endforeach
    </div>
    <br>
  @endif
  @if ($data['images_damage'])
    <div class="text-base">
      <strong>Fotos Detailaufnahme</strong><br>
      @foreach($data['images_damage'] as $key => $image)
        <a href="{{ env('APP_URL') . '/assets/' . $image }}" target="_blank">
          {{ \Str::replace('uploads_schadenmeldung/', '', $image) }}
        </a><br>
      @endforeach
    </div>
    <br>
  @endif
  <footer>
    <br>Carrosserie Sandtner AG<br>Speckstrasse 11<br>8330 Pfäffikon ZH
  </footer>
</x-mail::message>