<x-base-layout>
    <!-- start Modal -->
    <div class="modal fade" id="kt_modal_edit_card" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Payslip Type</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Your form or content for editing the payslip type goes here -->
                    <form action="" method="POST">
                        @csrf
                        @method('PUT')
                        <!-- Add form fields for editing the payslip type -->
                        <label for="name">Payslip Type Name:</label>
                        <input type="text" id="name" name="name" value="{{ $paysliptype->name }}" />
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- emd Modal -->
</x-base-layout>