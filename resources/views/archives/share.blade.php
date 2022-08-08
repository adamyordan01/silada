
    {{-- share document --}}
    <div class="modal fade share-document-modal" tabindex="-1" role="dialog" id="share-document-modal" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Bagikan Data Arsip</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="#" method="POST" id="form-share-document">
                    <div class="modal-body">
                        <div class="form-group mb-0">
                            <label for="">Bagikan Berkas Arsip</label>
                        </div>
                        @foreach ($positions as $position)
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" name="sk_pns" id="sk_pns" value="1">
                                <label class="form-check-label" for="sk_pns">{{ $positions->count() }}</label>
                            </div>
                        @endforeach
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" id="btn-share" value="share" class="btn btn-primary">Bagikan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>