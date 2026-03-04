@auth
    @php
        $companySetting = \App\Models\CompanySetting::where('status', 1)->orderBy('id', 'desc')->first();
        $data['company_name'] = $companySetting->company_name;
        $data['company_address'] = $companySetting->company_address;
        $data['company_mobile'] = $companySetting->company_mobile;
        $data['company_logo_one'] = $companySetting->company_logo_one;
        $imagePath = public_path(Storage::url($data['company_logo_one']));
        $imageDataUri = 'data:' . mime_content_type($imagePath) . ';base64,' . base64_encode(file_get_contents($imagePath));
    @endphp
@endauth

<table class="table table-borderless table-sm m-0">
    <tbody>
        <tr class="text-center">
            <td>
                <h5 class="py-0 my-0">{{ $data['company_name'] }}</h5>
                <p class="py-0">
                    {{ $data['company_address'] }}
                    <br>
                    {{ $data['company_mobile'] }}
                </p>
            </td>
        </tr>
    </tbody>
</table>
<table class="" style="position: absolute; margin-top: -95px;" align="start">
    <tbody>
        <tr class="text-center">
            <td>
                <img src="{{ $imageDataUri }}" alt="{{ $data['company_name'] }}" height="60" />
            </td>
        </tr>
    </tbody>
</table>
