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
<table style="width: 100%; border-spacing: 0; padding: 0; margin: 0;">
    <tr>
        {{-- Left: Logo --}}
        <td style="width: 80px; padding: 0; margin: 0; vertical-align: top;">
            <img src="{{ $imageDataUri }}" alt="{{ $data['company_name'] }}"
                 style="display: block; max-height: 60px; width: auto; object-fit: contain; margin: 0; padding: 0;" />
        </td>

        {{-- Middle: Company Info (top aligned to image) --}}
        <td class="text-center" style="vertical-align: top; padding-left: 10px;">
            <h5 style="text-transform: uppercase; margin: 0; padding: 0;">
                {{ $data['company_name'] }}
            </h5>
            <p style="margin: 0; padding: 0; line-height: 1.2;">
                {{ $data['company_address'] }}<br>
              
                {{ $data['company_mobile'] }}
            </p>
        </td>

        {{-- Right: Empty cell to balance layout --}}
        <td style="width: 80px; padding: 0; margin: 0;"></td>
    </tr>
</table>




