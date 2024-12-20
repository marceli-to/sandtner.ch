<x-mail::message>
  <div class="text-base">
    Kontaktanfrage
  </div>
  <br>
  @if ($data['subject'])
  <div class="text-base">
    <strong>Betreff</strong><br>
    {{ $data['subject'] }}
  </div>
  <br>
@endif
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
  @if ($data['message'])
    <div class="text-base">
      <strong>Nachricht</strong><br>
      {{ nl2br($data['message']) }}
    </div>
    <br>
  @endif
  <footer>
    <br>Carrosserie Sandtner AG<br>Speckstrasse 11<br>8330 Pfäffikon ZH
  </footer>
</x-mail::message>