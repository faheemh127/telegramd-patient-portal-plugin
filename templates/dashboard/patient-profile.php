<?php
// $user


$patient = [
    'full_name' => $user->data->display_name,
    'gender' => 'Male',
    'dob' => get_user_meta($user->ID, 'dob', true) ?: '1990-05-15',
    'email' => $user->data->user_email,
    'phone' => get_user_meta($user->ID, 'phone', true) ?: '+1 234-567-8900',
    'blood_group' => 'B+',
    'allergies' => 'Penicillin',
    'chronic_conditions' => 'Diabetes, Hypertension',
    'current_medications' => 'Metformin, Aspirin',
    'previous_surgeries' => 'Appendectomy (2012), Hernia Repair (2018)',
    'primary_physician' => 'Dr. Sarah Thompson',
    'address' => '123 Main St, New York, NY, USA',
    'emergency_contact' => 'Jane Doe - +1 234-555-7890',
];

$dob = new DateTime($patient['dob']);
$now = new DateTime();
$patient['age'] = $dob->diff($now)->y;

if (isset($_GET['message'])) {  ?>
    <div class="alert alert-success" role="alert">
        <?= esc_html($_GET['message']); ?>
    </div>
<?php } ?>

<div class="container my-5 profile-container">

    <div class="row g-4">
        <div class="col-md-12">

            <!-- Editable Account Details -->
            <h3>Account Details</h3>
            <div class="card mb-4 shadow-sm">
                <div class="card-body row row-cols-1 row-cols-md-1 g-3 p-4">
                    <form id="hld-account-details-form">
                        <div class="mb-3">
                            <label for="hld_full_name" class="form-label"><strong>Name:</strong></label>
                            <input type="text" class="form-control" id="hld_full_name" name="full_name" value="<?= esc_attr($patient['full_name']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="hld_email" class="form-label"><strong>Email:</strong></label>
                            <input type="email" class="form-control" id="hld_email" name="email" value="<?= esc_attr($patient['email']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="hld_phone" class="form-label"><strong>Phone:</strong></label>
                            <input type="text" class="form-control" id="hld_phone" name="phone" value="<?= esc_attr($patient['phone']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="hld_dob" class="form-label"><strong>Birthday:</strong></label>
                            <input type="date" class="form-control" id="hld_dob" name="dob" value="<?= esc_attr($patient['dob']); ?>">
                        </div>
                        <button type="button" id="hld_save_account_details" class="btn btn-primary">Save</button>
                        <span id="hld_account_details_message" style="display:none; margin-left:15px;"></span>
                    </form>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const saveBtn = document.getElementById('hld_save_account_details');
                    const form = document.getElementById('hld-account-details-form');
                    const msgSpan = document.getElementById('hld_account_details_message');
                    saveBtn.addEventListener('click', function() {
                        msgSpan.style.display = 'none';
                        saveBtn.disabled = true;
                        const data = {
                            full_name: form.full_name.value,
                            email: form.email.value,
                            phone: form.phone.value,
                            dob: form.dob.value
                        };
                        fetch('/wp-json/hld/v1/update-account-details', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-WP-Nonce': '<?php echo wp_create_nonce('wp_rest') ?>',
                                },
                                body: JSON.stringify(data)
                            })
                            .then(res => res.json())
                            .then(res => {
                                saveBtn.disabled = false;
                                msgSpan.style.display = 'inline-block';
                                if (res.success) {
                                    msgSpan.textContent = 'Account Details updated successfully.';
                                    msgSpan.style.color = 'green';
                                } else {
                                    msgSpan.textContent = res.message || 'Error updating details.';
                                    msgSpan.style.color = 'red';
                                }
                            })
                            .catch(() => {
                                saveBtn.disabled = false;
                                msgSpan.style.display = 'inline-block';
                                msgSpan.textContent = 'Error updating details.';
                                msgSpan.style.color = 'red';
                            });
                    });
                });
            </script>

            <h3>Payment Method</h3>
            <div class="card mb-4 shadow-sm">
                <div class="card-body row row-cols-1 row-cols-md-1 g-3 p-4">
                    <div class="d-flex justify-content-between">
                        <div><strong>Debit Card:</strong> <span class="fw-bold">**** **** **** 0000</span></div>
                        <button class="btn_payment_method btn_edit_settings">Add Payment Method</button>
                    </div>


                </div>
            </div>
            <h3>Shipping Address</h3>

            <div class="card mb-4 shadow-sm">
                <div class="card-body row row-cols-1 row-cols-md-1 g-3 p-4">
                    <div><strong>Orders:</strong> <span class="fw-bold">Need to change the address of an order that's in-progress? </span> <a href="#">Contact customer support</a></div>
                    <div class="text-secondary">subscriptions</div>
                    <div class="text-danger">Update your shipping address in your <a href="">subscriptions page</a></div>

                </div>
            </div>

            <!-- <a href="" class="hld_btn_profile_logout">logout</a> -->
            <a href="<?= wp_logout_url(home_url('?message=User+logged+out')); ?>" class="hld_btn_profile_logout">logout</a>


            <!-- Medical Information -->
            <!-- <div class="card mb-4 shadow-sm">
                <div class="card-header text-primary fw-bold">Medical Information</div>
                <div class="card-body row row-cols-1 row-cols-md-2 g-3">
                    <div><strong>Blood Group:</strong> <?= $patient['blood_group']; ?></div>
                    <div><strong>Allergies:</strong> <?= $patient['allergies']; ?></div>
                    <div><strong>Chronic Conditions:</strong> <?= $patient['chronic_conditions']; ?></div>
                    <div><strong>Current Medications:</strong> <?= $patient['current_medications']; ?></div>
                    <div><strong>Previous Surgeries:</strong> <?= $patient['previous_surgeries']; ?></div>
                    <div><strong>Primary Physician:</strong> <?= $patient['primary_physician']; ?></div>
                </div>
            </div> -->

            <!-- Emergency & Contact -->
            <!-- <div class="card shadow-sm">
                <div class="card-header text-primary fw-bold">Emergency & Contact</div>
                <div class="card-body row row-cols-1 row-cols-md-2 g-3">
                    <div><strong>Address:</strong> <?= $patient['address']; ?></div>
                    <div><strong>Emergency Contact:</strong> <?= $patient['emergency_contact']; ?></div>
                </div>
            </div> -->
        </div>
    </div>
</div>