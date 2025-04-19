@empty($borrow)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5> Data peminjaman yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/borrow') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/borrow/' . $borrow->id . '/update_ajax') }}" method="POST" id="form-edit">
        @csrf @method('PUT')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Peminjaman</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label>Peminjam (User)</label>
                        <select name="user_id" class="form-control" required>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ $borrow->user_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        <small id="error-user_id" class="error-text form-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label>Buku</label>
                        <select name="book_id" class="form-control" required>
                            @foreach ($books as $book)
                                <option value="{{ $book->id }}" {{ $borrow->book_id == $book->id ? 'selected' : '' }}>
                                    {{ $book->title }}
                                </option>
                            @endforeach
                        </select>
                        <small id="error-book_id" class="error-text form-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Pinjam</label>
                        <input value="{{ $borrow->borrow_date }}" type="date" name="borrow_date" class="form-control" required>
                        <small id="error-borrow_date" class="error-text form-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label>Jatuh Tempo</label>
                        <input value="{{ $borrow->due_date }}" type="date" name="due_date" class="form-control" required>
                        <small id="error-due_date" class="error-text form-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Kembali</label>
                        <input value="{{ $borrow->returned_at }}" type="date" name="returned_at" class="form-control" required>
                        <small id="error-returned_at" class="error-text form-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control" required>
                            <option value="dipinjam" {{ $borrow->status == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                            <option value="dikembalikan" {{ $borrow->status == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                        </select>
                        <small id="error-status" class="error-text form-text text-danger"></small>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </form>

    <script>
        $(document).ready(function () {
            $("#form-edit").validate({
                rules: {
                    user_id: { required: true },
                    book_id: { required: true },
                    borrow_date: { required: true, date: true },
                    due_date: { required: true, date: true },
                    return_date: { required: true, date: true },
                    status: { required: true }
                },
                submitHandler: function (form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function (response) {
                            if (response.status) {
                                $('#myModal').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                });
                                dataBorrow.ajax.reload();
                            } else {
                                $('.error-text').text('');
                                $.each(response.msgField, function (prefix, val) {
                                    $('#error-' + prefix).text(val[0]);
                                });
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan',
                                    text: response.message
                                });
                            }
                        }
                    });
                    return false;
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
@endempty
