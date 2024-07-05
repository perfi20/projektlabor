<?php 

class Toast {

    public function display() {
        echo '
            <div class="toast-container position-fixed bottom-0 end-0 p-3">
                <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header" id="toastHeader">
                        <strong class="me-auto" id="toastTitle"></strong>
                        <small>now</small>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body" id="toastMessage">
            
                    </div>
                </div>
            </div>
        ';
    }
}

?>
