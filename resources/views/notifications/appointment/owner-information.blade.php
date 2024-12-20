<x-mail::message>
  <div class="text-base">
    Terminanfrage
  </div>
  <br>
  @if ($data['appointment_date'])
    <div class="text-base">
      <strong>Datum</strong><br>
      {{ $data['appointment_date'] }}
    </div>
    <br>
  @endif
  @if ($data['appointment_time'])
    <div class="text-base">
      <strong>Zeit</strong><br>
      {{ $data['appointment_time'] }}
    </div>
    <br>
  @endif
  @if ($data['appointment_reason'])
    <div class="text-base">
      <strong>Grund</strong><br>
      {{ $data['appointment_reason'] }}
    </div>
    <br>
  @endif
  @if ($data['appointment_control_plate'])
    <div class="text-base">
      <strong>Kontrollschild</strong><br>
      {{ $data['appointment_control_plate'] }}
    </div>
    <br>
  @endif
  @if ($data['appointment_firstname'])
    <div class="text-base">
      <strong>Vorname</strong><br>
      {{ $data['appointment_firstname'] }}
    </div>
    <br>
  @endif
  @if ($data['appointment_name'])
    <div class="text-base">
      <strong>Name</strong><br>
      {{ $data['appointment_name'] }}
    </div>
    <br>
  @endif
  @if ($data['appointment_email'])
    <div class="text-base">
      <strong>E-Mail</strong><br>
      {{ $data['appointment_email'] }}
    </div>
    <br>
  @endif
  @if ($data['appointment_phone'])
    <div class="text-base">
      <strong>Telefon</strong><br>
      {{ $data['appointment_phone'] }}
    </div>
    <br>
  @endif
  @if ($data['appointment_message'])
    <div class="text-base">
      <strong>Nachricht</strong><br>
      {{ nl2br($data['appointment_message']) }}
    </div>
    <br>
  @endif
  <footer>
    <br>Carrosserie Sandtner AG<br>Speckstrasse 11<br>8330 Pfäffikon ZH
  </footer>
</x-mail::message>