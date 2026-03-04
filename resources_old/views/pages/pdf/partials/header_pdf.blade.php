@auth
    @php
        $companySetting = \App\Models\CompanySetting::where('status', 1)->orderBy('id', 'desc')->first();
        $data['company_name'] = $companySetting->company_name;
        $data['company_address'] = $companySetting->company_address;
        $data['factory_address'] = $companySetting->factory_address;
        $data['company_mobile'] = $companySetting->company_mobile;
        $data['company_logo_one'] = $companySetting->company_logo_two;
        $imagePath = public_path(Storage::url($data['company_logo_one']));
        $imageDataUri = 'data:' . mime_content_type($imagePath) . ';base64,' . base64_encode(file_get_contents($imagePath));
    @endphp
@endauth
<style>
    .pdf_title {
        text-transform: uppercase;  /* Ensure all letters are capital */
        font-size: 14px !important;
        border: 1px solid;  /* Add border */
        display: inline-block;  /* Ensure border wraps only around the text */
        padding: 2px 5px;  /* Optional: Add padding to give space between text and border */
    }
</style>
<table class="table table-borderless table-sm mb-0 pb-0" style=" margin-top: -35px;">
    <tbody>
        <tr class="text-center">
            <td>
                <h5 class="py-0 my-0" style="text-transform: uppercase;">{{ $data['company_name'] }}</h5>
                <p class="py-0 mb-1">
                    {{ $data['company_address'] }}
                    <br>
                    {{ $data['company_mobile'] }}
                </p>
            </td>
        </tr>
    </tbody>
</table>
<table class="mb-0 pb-0" style="position: absolute; margin-top: -70px;"  align="start">
    <tbody>
        <tr class="text-center">
            <td>
                <img src="{{ $imageDataUri }}" alt="{{ $data['company_name'] }}" height="60" />
            </td>
        </tr>
    </tbody>
</table>
