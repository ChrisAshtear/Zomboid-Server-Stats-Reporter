import React, { Component } from 'react';
import './App.css';
import './cui.css';
import axios from 'axios';
import {Image,Card,CardDeck,CardColumns,CardBody,CardText,CardTitle,CardHeader,Row,Col,Container} from 'reactstrap';
import  BGImage from './bg.jpg';
import  Logo from './logo.png';

class App extends Component {
  constructor(props) {
	  super(props);
      this.state = {
        fetchData: [{username:'test1',charname:'test2',lastOnline:""},{username:'test1',charname:'test2',lastOnline:""},{username:'test1',charname:'test2',lastOnline:""},{username:'test1',charname:'test2',lastOnline:""},{username:'test1',charname:'test2',lastOnline:"11/23/81"},{username:'test1',charname:'test2',lastOnline:""}],
		servData: {dayofmonth:2,month:3,daysSinceStart:20,year:1993,name:"ServerName",description:"description",curPlayers:"NA",maxPlayers:0,curPlayers:0},
		servName: '',
      }
	  window.app = this;
	  window.host = process.env.BACKEND_HOST;
	  window.port = process.env;
	}
  
	handleChange = (event) => {
	  let nam = event.target.name;
	  let val = event.target.value
	  this.setState({
		[nam]: val
	  })
	}
	
	componentDidMount() {
      console.log({BGImage});
	  document.body.style.backgroundImage = `url(${BGImage})`;
	  axios.get("/api/getplayers")
		  .then((response) => {
			  this.setState({
				  fetchData: response.data
			  })
		  })
	  axios.get("/api/getserver")
		  .then((response) => {
			  this.setState({
				  servData: response.data[0]
			  })
		  })
	}
	
	render() {
	  let card = this.state.fetchData.map((val, key) => {
		  var x = new Date(val.lastOnline);
		  var lastOnlineDate = x.toLocaleDateString("default", { year: "numeric", month: "2-digit",  day: "2-digit",});
		  return (
			  <React.Fragment>
				  <Card >
					  <CardBody>
						  <CardTitle>{val.username}</CardTitle>
							  {val.charname}
							  <br/>
							  Last Online - {lastOnlineDate}
					  </CardBody>
				  </Card>
			  </React.Fragment>
		  )
	  })
	  var gameDate = new Date(this.state.servData.year+"-"+this.state.servData.month+"-"+this.state.servData.dayofmonth);

	  return (
		  <div className='App animated fadeIn'>
		  <br/>
			  <img src={Logo}/>

			  <h1 className='serverTitle'>{this.state.servData.name}</h1>
				
			  <h3 className='serverTitle'>{this.state.servData.description}</h3>

			  <Container>
			  <CardDeck>
			  <Card className='m-2'>
					<CardHeader className='h3'>
                        In-Game Date
                    </CardHeader>
					  <CardBody className='clock-bg'>
						  <CardText className='font-clock clock-bg h1'>{gameDate.toLocaleDateString("default", {year: "2-digit", month: "2-digit", day: "2-digit"})}</CardText>
					  </CardBody>
			  </Card>
			  <Card className='m-2'>
					<CardHeader className='h3'>
                        Max Players
                    </CardHeader>
					  <CardBody>
						  <CardText className='h1'>{this.state.servData.maxPlayers}</CardText>
						  //<CardText className='h1'>{this.state.servData.curPlayers}/{this.state.servData.maxPlayers}</CardText>
						  //No current function to retrieve current player amount so just display max players
					  </CardBody>
			  </Card>
			  <Card className='m-2'>
					<CardHeader className='h3'>
                        Days Since Apocalypse
                    </CardHeader>
					  <CardBody>
						  <CardText className='h1'>{this.state.servData.daysSinceStart}</CardText>
					  </CardBody>
			  </Card>
			  </CardDeck>
			  </Container>
			  <Container>
				  <Card ><CardHeader className='h2'>Players</CardHeader>
				  <CardBody>
				  <CardColumns>
					  {card}
				  </CardColumns>
				  </CardBody>
				  </Card>
			  </Container>
		  </div>
	  );
	}
}
export default App;

