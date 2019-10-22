<div id="modal-toggle" uk-modal>
    <div class="uk-modal-dialog">

        <button class="uk-modal-close-default" type="button" uk-close></button>



        <div class="uk-modal-header">
            <h3 class="uk-modal-title">Settings</h3>
        </div>

        <div class="uk-margin-medium-top">
            <ul class="uk-flex-center" uk-tab>
                <li class="uk-active"><a onclick="openPage('profile')">Profile</a></li>
                <li><a onclick="openPage('password')">Password</a></li>
                <li><a onclick="openPage('delete')">Delete</a></li>
            </ul>
        </div>

        <div class="uk-modal-body" uk-overflow-auto>

            <div class="page" id="profile">

                <?php include '../includes/user_info.php' ?>


                <div class="uk-margin">
                    <label class="uk-form-label" for="form-stacked-text">Username</label>
                    <div class="uk-form-controls">
                        <input placeholder="Username" class="uk-input" id="username" type="text" value="<?=$username ?>"
                            required>
                    </div>
                </div>

                <button id="submitProfile" class="uk-button uk-button-primary uk-align-right">Save
                    Profile</button>

            </div>

            <div style="display: none" class="page" id="password">



                <div class="uk-margin">
                    <label class="uk-form-label" for="form-stacked-text">Old Password</label>
                    <div class="uk-form-controls">
                        <input class="uk-input" id="oldPassword" type="password" placeholder="Password" required>
                    </div>
                </div>

                <div class="uk-margin">
                    <label class="uk-form-label" for="form-stacked-text">New Password</label>
                    <div class="uk-form-controls">
                        <input class="uk-input" id="newPassword" type="password" placeholder="New Password" required>
                    </div>
                </div>

                <div class="uk-margin">
                    <label class="uk-form-label" for="form-stacked-text">Retype New Password</label>
                    <div class="uk-form-controls">
                        <input class="uk-input" id="newPasswordVerif" type="password" placeholder="Retype New Password"
                            required>
                    </div>
                </div>

                <button id="submitPassword" class="uk-button uk-button-primary uk-align-right">Save Password</button>



            </div>

            <div style="display: none" class="page" id="delete">

                <p>If you delete your account there will be no way to recover it, all your data will be deleted as well
                    as all your links</p>
                <button id="deleteAccount" class="uk-button uk-button-danger uk-align-center">Delete your account</button>

            </div>


        </div>
    </div>
</div>