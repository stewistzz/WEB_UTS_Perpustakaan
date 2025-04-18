@empty($book)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5> Data buku yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/book') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/book/' . $book->id . '/update_ajax') }}" method="POST" id="form-edit">
        @csrf @method('PUT')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Buku</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label>Judul Buku</label>
                        <input value="{{ $book->title }}" type="text" name="title" class="form-control" required>
                        <small id="error-title" class="error-text form-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label>Penulis</label>
                        <input value="{{ $book->author }}" type="text" name="author" class="form-control" required>
                        <small id="error-author" class="error-text form-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label>Penerbit</label>
                        <input value="{{ $book->publisher }}" type="text" name="publisher" class="form-control" required>
                        <small id="error-publisher" class="error-text form-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label>Tahun Terbit</label>
                        <input value="{{ $book->year }}" type="number" name="year" class="form-control" required>
                        <small id="error-year" class="error-text form-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label>Stok</label>
                        <input value="{{ $book->stock }}" type="number" name="stock" class="form-control" required>
                        <small id="error-stock" class="error-text form-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="category_id" class="form-control" required>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $book->category_id == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        <small id="error-category_id" class="error-text form-text text-danger"></small>
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
                    title: { required: true, minlength: 2 },
                    author: { required: true, minlength: 2 },
                    publisher: { required: true },
                    year: { required: true, digits: true },
                    stock: { required: true, digits: true },
                    category_id: { required: true }
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
                                dataBook.ajax.reload();
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
