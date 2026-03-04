<footer class="footer fixed-bottom text-center" style="margin-bottom: -30px;">
    <table class="table">
        <tr>
            <td style="border: none; border-bottom: none; border-top: none;">
                Design and Developed By: NRB SOFTWARE.
            </td>
            <td style="border: none; border-bottom: none; border-top: none;">
                @php
                    echo date('l, F j, Y h:i A');
                @endphp
            </td>
        </tr>
    </table>
</footer>
<script type="text/php">
    if (isset($pdf)) {
        $font = $fontMetrics->get_font("helvetica", "normal");
        $size = 8;
        $pdf->page_text(500, 797, "Page {PAGE_NUM} of {PAGE_COUNT}", $font, $size, array(0,0,0));
    }
</script>
