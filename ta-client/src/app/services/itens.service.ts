import { Injectable } from '@angular/core';
import { Observable, of, throwError } from 'rxjs';
import { HttpClient, HttpHeaders, HttpErrorResponse } from '@angular/common/http';
import { catchError, tap, map } from 'rxjs/operators';
import 'rxjs/add/operator/map';
import 'rxjs/add/operator/toPromise';

import { ItensModel } from 'app/models/itens.model';

import { API_URL } from 'app/app.api';

const httpOptions = {
  headers: new HttpHeaders({'Content-Type': 'application/json'})
};

@Injectable({
  providedIn: 'root'
})
export class ItensService {

  result: any;

  constructor(private http: HttpClient) { }

  /* itensGetAll() {
    this.http.get(`${API_URL}items`)
      .map(response => response.json())
      .subscribe(
      data => {
        this.result = this.result.concat(data)
      },
      err => {
        alert('Deu erro!');
      })
  } */

  /* itensGetAll(): Observable<ItensModel[]> {
    return this.http.get<ItensModel[]>(`${API_URL}items`)
      .pipe(
        tap(itens => console.log('fetched products')),
        catchError(this.handleError('getItems', []))
      );
  } */

  /* itensGetAll() {
    if(this.data) {
      return Promise.resolve(this.data);
    }

    return new Promise(resolve => {
      this.http.get(`${API_URL}items`)
      .map(res => res)
      .subscribe(data => {
        this.data = data;
        resolve(this.data);
      });
    })
  } */

  /* itensGetAll() {
    let promise = new Promise((resolve, reject) => {
      this.http.get(`${API_URL}items`)
        .toPromise()
        .then(
          res => {
            this.data = res;
            resolve();
          },
          err => {
            reject();
          }
        )
    });
    return promise;
  } */

  private handleError<T> (operation = 'operation', result?: T) {
    return (error: any): Observable<T> => {
  
      // TODO: send the error to remote logging infrastructure
      console.error(error); // log to console instead
  
      // Let the app keep running by returning an empty result.
      return of(result as T);
    };
  }
  
}
