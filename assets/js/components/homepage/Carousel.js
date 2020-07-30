import React from 'react';

import GreenButton from '../GreenButton';
import Image1 from '../../../../public/img/slider/slider1.jpg';
import Image2 from '../../../../public/img/slider/slider2.jpg';
import Image3 from '../../../../public/img/slider/slider3.jpg';

const Carousel = () => {
  const items = [
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
    <div
      id="carouselExampleIndicators"
      className="carousel slide"
      data-ride="carousel">
      <ol className="carousel-indicators">
        <li
          data-target="#carouselExampleIndicators"
          data-slide-to="0"
          className="active"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
      </ol>
      <div className="carousel-inner">
        <div className="carousel-item active">
          <img
            src={items[0].image}
            className="d-block w-100 carousel-item_img"
            alt="Luxembourg City"
          />
          <hgroup className="carousel-caption">
            <h1 className="heading-huge">{items[0].name}</h1>
            <h2 className="heading-small mb-5">{items[0].description}</h2>
            <GreenButton text="Our Services" />
          </hgroup>
        </div>
        <div className="carousel-item">
          <img
            src={items[1].image}
            className="d-block w-100 carousel-item_img"
            alt="Luxembourg City"
          />
          <hgroup className="carousel-caption">
            <h1 className="heading-huge">{items[1].name}</h1>
            <h2 className="heading-small mb-5">{items[1].description}</h2>
            <GreenButton text="Our Services" />
          </hgroup>
        </div>
        <div className="carousel-item">
          <img
            src={items[2].image}
            className="d-block w-100 carousel-item_img"
            alt="Luxembourg City"
          />
          <hgroup className="carousel-caption">
            <h1 className="heading-huge">{items[2].name}</h1>
            <h2 className="heading-small mb-5">{items[2].description}</h2>
            <GreenButton text="Our Services" />
          </hgroup>
        </div>
      </div>
      <a
        className="carousel-control-prev"
        href="#carouselExampleIndicators"
        role="button"
        data-slide="prev">
        <span className="carousel-control-prev-icon" aria-hidden="true"></span>
        <span className="sr-only">Previous</span>
      </a>
      <a
        className="carousel-control-next"
        href="#carouselExampleIndicators"
        role="button"
        data-slide="next">
        <span className="carousel-control-next-icon" aria-hidden="true"></span>
        <span className="sr-only">Next</span>
      </a>
    </div>
  );
};

export default Carousel;
