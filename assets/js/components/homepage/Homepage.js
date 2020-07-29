import React from 'react';

import Carousel from './Carousel';
import AboutUs from './AboutUs';
import OurServices from './OurServices';
import ContactUs from './ContactUs';
import FollowBanner from '../FollowBanner';

const Homepage = () => {
  return (
    <div>
      <Carousel />
      <AboutUs />
      <OurServices />
      <ContactUs />
      <FollowBanner />
    </div>
  );
};

export default Homepage;
