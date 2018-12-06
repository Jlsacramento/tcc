import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import 'rxjs/add/operator/do';
import 'rxjs/add/operator/toPromise';

import { API_URL } from 'app/app.api';
import { Observable, BehaviorSubject } from 'rxjs';
import { Router } from '@angular/router';

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  private loggedIn = new BehaviorSubject<boolean>(false);

  get isLoggedIn() {
    return this.loggedIn.asObservable();
  }

  constructor(private http: HttpClient, private router: Router) { }

  check(): boolean {
    return localStorage.getItem('token') ? true : false;
  }

  login(credentials: {email: string, password: string}): Observable<boolean> {
    return this.http.post<any>(`${API_URL}auth/login`, credentials)
      .do(data => {
        this.loggedIn.next(true);
        localStorage.setItem('token', data.token);
      })
  }

  logout(): void {
    this.http.get(`${API_URL}auth/logout`).subscribe(resp => {
      console.log(resp);
      localStorage.clear();
      this.loggedIn.next(false);
      this.router.navigate(['login']);
    })
  }

  setUser(): Promise<boolean> {
    return this.http.get<any>(`${API_URL}auth/logout`).toPromise()
      .then(data => {
        if(data.user) {
          localStorage.setItem('user', data.user);
          return true;
        }
        return false;
      });
  }

}
