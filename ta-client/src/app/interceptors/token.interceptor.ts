import { Injectable } from '@angular/core';
import {
  HttpEvent, HttpInterceptor, HttpHandler, HttpRequest
} from '@angular/common/http';

import { Observable } from 'rxjs';
import { API_URL } from 'app/app.api';

/** Pass untouched request through to the next request handler. */
@Injectable()
export class TokenInterceptor implements HttpInterceptor {

  intercept(request: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
    const requestUrl: Array<any> = request.url.split('/');
    const apiUrl: Array<any> = API_URL.split('/');
    const token = localStorage.getItem('token');

    if(token && (requestUrl[2] === apiUrl[2])) {
        const newRequest = request.clone({ setHeaders: {'Authorization': `Bearer ${token}`} });
        return next.handle(newRequest);
    }else {
        return next.handle(request);
    }
  }
}