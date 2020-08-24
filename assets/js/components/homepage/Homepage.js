import React from 'react';

import Carousel from './Carousel';
import AboutUs from './AboutUs';
import OurServices from './OurServices';
import ContactUs from './ContactUs';
import FollowBanner from '../FollowBanner';

/*********
COMPOSANTS SITE VITRINE
Each component contains a section of the website
Hardcoded text
*********/
const Homepage = () => {
  return (
    <main className="grid-container">
      <Carousel />
      <AboutUs />
      <OurServices />
      <ContactUs />
      <FollowBanner />
    </main>
  );
};

export default Homepage;
