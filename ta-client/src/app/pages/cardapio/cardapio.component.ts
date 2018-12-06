import { Component, OnInit } from '@angular/core';
import { ItensService } from 'app/services/itens.service';
import { ItensModel } from 'app/models/itens.model';
import { HttpClient } from '@angular/common/http';

export interface PeriodicElement {
  name: string;
  position: number;
  weight: number;
  symbol: string;
}

const ELEMENT_DATA: PeriodicElement[] = [
  {position: 1, name: 'Hydrogen', weight: 1.0079, symbol: 'H'},
  {position: 2, name: 'Helium', weight: 4.0026, symbol: 'He'},
  {position: 3, name: 'Lithium', weight: 6.941, symbol: 'Li'},
  {position: 4, name: 'Beryllium', weight: 9.0122, symbol: 'Be'},
  {position: 5, name: 'Boron', weight: 10.811, symbol: 'B'},
  {position: 6, name: 'Carbon', weight: 12.0107, symbol: 'C'},
  {position: 7, name: 'Nitrogen', weight: 14.0067, symbol: 'N'},
  {position: 8, name: 'Oxygen', weight: 15.9994, symbol: 'O'},
  {position: 9, name: 'Fluorine', weight: 18.9984, symbol: 'F'},
  {position: 10, name: 'Neon', weight: 20.1797, symbol: 'Ne'},
];

@Component({
  selector: 'app-cardapio',
  templateUrl: './cardapio.component.html',
  styleUrls: ['./cardapio.component.scss']
})
export class CardapioComponent implements OnInit {

  /* displayedColumns: string[] = ['position', 'name', 'weight', 'symbol'];
  itens = ELEMENT_DATA; */

  displayedColumns: string[] = ['tip_ite_id', 'ite_nome', 'ite_preco'];
  itens: ItensModel[];
  isLoadingResults = true;

  constructor(private itensService: ItensService, private http: HttpClient) { }

  ngOnInit() {
    //this.loadItemsList();
    this.http.get<ItensModel[]>('http://127.0.0.1:8000/api/items')
    .subscribe(response => this.itens = response);
    console.log('Itens ', this.itens);
  }

  /* loadItemsList() {
    this.itensService.itensGetAll()
      .subscribe(res => {
        this.itens = res;
        console.log(this.itens);
        this.isLoadingResults = false;
      }, err => {
        console.log(err);
        this.isLoadingResults = false;
      });
  } */

  /* loadItemsList() {
    this.itensService.itensGetAll()
      .then(data => {
        this.itens = data;
        this.isLoadingResults = false;
        console.log('Then ', this.itens);
        console.log('Data ', data);
      });
  } */

}
