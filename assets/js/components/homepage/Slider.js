import React from 'react';
//import Carousel from 'react-material-ui-carousel';
//import { Button, CardMedia, Grid } from '@material-ui/core';

//import useStyles from './styles';
import Image1 from '../../../../public/img/slider/slider1.jpg';
import Image2 from '../../../../public/img/slider/slider2.jpg';
import Image3 from '../../../../public/img/slider/slider3.jpg';

function Slider(props) {
  var items = [
    {
      name: 'Lead by example',
      description:
        'As an employer, we want our employees to be happy so that they can give their best for our clients',
      image: Image1,
    },
    {
      name: 'We want to be a reliable partner for you',
      description: 'Quality and efficiency are among our core values',
      image: Image2,
    },
    {
      name: ' A partner for all your needs',
      description:
        'You need experienced staff for your projects or for operational needs? Our job is to provide you with the most suitable people',
      image: Image3,
    },
  ];

  return (
    <Carousel navButtonsAlwaysVisible={true} autoPlay={false} animation="slide">
      {items.map((item, i) => (
        <Item key={i} item={item} />
      ))}
    </Carousel>
  );
}

function Item(props) {
  const classes = useStyles();
  return (
    <CardMedia
      image={props.item.image}
      title="Luxembourg City"
      className={classes.img}>
      <Grid
        container={true}
        justify="center"
        alignContent="center"
        direction="column"
        className={classes.slider}>
        <div className={classes.content}>
          <h1>{props.item.name}</h1>
          <p> {props.item.description}</p>
        </div>
        <Button className="CheckButton">Check it out!</Button>
      </Grid>
    </CardMedia>
  );
}

export default Slider;
/* style={{
  background: `linear-gradient( rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5) ), url(${props.item.image}) 100% 100%`,
}} */
