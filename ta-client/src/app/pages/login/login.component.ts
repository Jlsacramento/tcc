import { Component, OnInit } from '@angular/core';
import { FormGroup, FormControl, Validators, FormBuilder } from '@angular/forms';
import { UsuariosModel } from 'app/models/usuarios.model';
import { AuthService } from 'app/services/auth.service';
import { HttpErrorResponse } from '@angular/common/http';
import { Router } from '@angular/router';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {

  f: FormGroup;
  user: UsuariosModel;
  errorCredentials = false;


  constructor(
    private formBuilder: FormBuilder,
    private authService: AuthService,
    private router: Router
    ) { }

  ngOnInit() {
    this.createFormGroup();
  }

  createFormGroup() {
    this.f = this.formBuilder.group({
      email: [null, [Validators.required, Validators.email]],
      password: [null, [Validators.required]]
    });
  }

  onSubmit() {
    this.authService.login(this.f.value).subscribe(
      (response) => { 
        this.router.navigate(['dashboard']);
       },
      (errorResponse: HttpErrorResponse) => {
        if(errorResponse.status === 401) {
          this.errorCredentials = true;
        }
      }
    )
  }

}
