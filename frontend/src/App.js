import React, { Component } from 'react';
import './App.css';
import './cui.css';
import axios from 'axios';
import {Image,Card,CardDeck,CardBody,CardText,CardTitle,CardHeader,Row,Col,Container} from 'reactstrap';
import  BGImage from './bg.jpg';
import  Logo from './logo.png';

class App extends Component {
  constructor(props) {
	  super(props);
      this.state = {
        fetchData: [],
		servData: {dayofmonth:29,month:12,daysSinceStart:20,name:"ServerName",description:"description"},
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
	  document.body.backgroundImage = `url(${BGImage})`;
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
		  return (
			  <React.Fragment>
				  <Card style={{ width: '18rem' }} className='m-2'>
					  <CardBody>
						  <CardTitle>{val.username}</CardTitle>
							  {val.charname}
							  <br/>
							  Last Online - {x.toDateString()}
					  </CardBody>
				  </Card>
			  </React.Fragment>
		  )
	  })

	  return (
		  <div className='App animated-fadein'>
		  <br/>
			  <img src={Logo}/>
			  <Card className='m-2'>
			  <CardHeader ><h1>{this.state.servData.name}</h1></CardHeader>
			  <CardBody>
			  {this.state.servData.description}
			  </CardBody>
			  </Card>
			  <Container>
			  <CardDeck>
			  <Card className='m-2'>
					<CardHeader className='h2'>
                        In-Game Date
                    </CardHeader>
					  <CardBody className='clock-bg'>
						  <CardText className='font-clock clock-bg h1'>{this.state.servData.month}/{this.state.servData.dayofmonth}</CardText>
					  </CardBody>
			  </Card>
			  <Card className='m-2'>
					<CardHeader className='h2'>
                        Current Players
                    </CardHeader>
					  <CardBody>
						  <CardText className='h1'>15/32</CardText>
					  </CardBody>
			  </Card>
			  <Card className='m-2'>
					<CardHeader className='h2'>
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
				  <Row>
					  {card}
				  </Row>
				  </Card>
			  </Container>
		  </div>
	  );
	}
}
export default App;

