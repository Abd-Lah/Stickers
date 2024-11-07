<div class="modal fade theme-modal remove-coupon" id="confirmationModal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header d-block text-center">
                <h5 class="modal-title w-100">Are You Sure?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="remove-box">
                    <p id="delete-message"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-animation btn-md fw-bold" data-bs-dismiss="modal">No</button>
                <form id="delete-form" method="post">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-animation btn-md fw-bold">Yes</button>
                </form>
            </div>
        </div>
    </div>
</div>
